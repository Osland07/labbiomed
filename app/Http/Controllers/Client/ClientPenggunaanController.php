<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanRequest;
use App\Models\Alat;
use App\Models\Ruangan;
use App\Models\Laporan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\AutoValidate;
use App\Models\LaporanPeminjaman;

class ClientPenggunaanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:penggunaan-alat-client')->only(['indexAlat']);
        $this->middleware('permission:penggunaan-ruangan-client')->only(['indexRuangan']);
    }

    public function indexAlat()
    {
        // Ambil laporan alat aktif user (status Diterima, Belum Dikembalikan)
        // Exclude yang sudah dikembalikan user (meskipun belum divalidasi admin)
        $laporanAlatAktif = \App\Models\Laporan::where('user_id', Auth::id())
            ->whereNotNull('alat_id')
            ->where('status_peminjaman', 'Diterima')
            ->where(function($query) {
                $query->where('status_pengembalian', 'Belum Dikembalikan')
                      ->orWhereNull('status_pengembalian');
            })
            ->whereNull('tgl_pengembalian') // Hanya yang belum dikembalikan user
            ->with(['alat', 'alat.ruangan'])
            ->get();

        // Ambil semua alat yang tersedia untuk user (berdasarkan laporan yang sudah selesai)
        $today = Carbon::today();
        $allSelesai = LaporanPeminjaman::where('user_id', Auth::user()->id)
            ->where('status_validasi', LaporanPeminjaman::STATUS_SELESAI)
            ->whereNotNull('surat')
            ->whereDate('tgl_pengembalian', '>=', $today)
            ->get();

        $alatIds = collect($allSelesai->pluck('alat_id'))
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $alats = Alat::with('ruangan')->whereIn('id', $alatIds)->get();

        return view("client.penggunaan-alat.index", compact('alats', 'laporanAlatAktif'));
    }

    public function storeAlat(LaporanRequest $request)
    {
        // Validasi tambahan untuk waktu
        $request->validate([
            'tanggal_penggunaan' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addWeek()->toDateString(),
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
        ], [
            'tanggal_penggunaan.required' => 'Tanggal penggunaan wajib diisi.',
            'tanggal_penggunaan.after_or_equal' => 'Tanggal penggunaan tidak boleh kurang dari hari ini.',
            'tanggal_penggunaan.before_or_equal' => 'Tanggal penggunaan tidak boleh lebih dari 1 minggu ke depan.',
            'waktu_selesai.after' => 'Waktu selesai harus lebih besar dari waktu mulai.',
        ]);

        // Validasi waktu operasional (08:00 - 17:00)
        $waktuMulai = $request->input('waktu_mulai');
        $waktuSelesai = $request->input('waktu_selesai');
        
        if ($waktuMulai < '08:00' || $waktuMulai > '17:00') {
            return redirect()->back()->withErrors(['waktu_mulai' => 'Waktu mulai harus antara 08:00 - 17:00.'])->withInput();
        }
        
        if ($waktuSelesai < '08:00' || $waktuSelesai > '17:00') {
            return redirect()->back()->withErrors(['waktu_selesai' => 'Waktu selesai harus antara 08:00 - 17:00.'])->withInput();
        }

        $alatIds = $request->input('alat_id');

        // Pastikan ini adalah array
        if (!is_array($alatIds)) {
            $alatIds = explode(',', $alatIds);
        }

        // Validasi manual apakah semua alat ID valid
        $alatValid = Alat::whereIn('id', $alatIds)->pluck('id')->toArray();
        $alatInvalid = array_diff($alatIds, $alatValid);
        if (count($alatInvalid)) {
            return redirect()->back()->withErrors(['alat_id' => 'Beberapa alat tidak valid atau tidak tersedia.'])->withInput();
        }

        $laporans = [];
        $userId = Auth::id();
        
        // Gabungkan tanggal dengan waktu
        $tanggal = $request->input('tanggal_penggunaan');
        $waktuMulaiStr = $request->input('waktu_mulai');
        $waktuSelesaiStr = $request->input('waktu_selesai');
        
        $waktuMulai = Carbon::parse($tanggal . ' ' . $waktuMulaiStr);
        $waktuSelesai = Carbon::parse($tanggal . ' ' . $waktuSelesaiStr);
        $tujuan = $request->input('tujuan_penggunaan');

        foreach ($alatIds as $alatId) {
            // Cek jika laporan identik sudah ada
            $existing = Laporan::where('user_id', $userId)
                ->where('alat_id', $alatId)
                ->where('waktu_mulai', $waktuMulai)
                ->where('waktu_selesai', $waktuSelesai)
                ->where('tujuan_penggunaan', $tujuan)
                ->whereIn('status_peminjaman', ['Menunggu', 'Diterima'])
                ->first();

            if ($existing) {
                continue; // skip jika sudah ada laporan serupa
            }

            $laporan = Laporan::create([
                'user_id'             => $userId,
                'alat_id'             => $alatId,
                'waktu_mulai'         => $waktuMulai,
                'waktu_selesai'       => $waktuSelesai,
                'tujuan_penggunaan'   => $tujuan,
                'status_peminjaman'   => 'Menunggu',
                'status_pengembalian' => 'Belum Dikembalikan',
                'tipe'                => 'alat',
            ]);

            // Ubah status alat
            $alat = Alat::findOrFail($alatId);
            $alat->status = 'Sedang Digunakan';
            $alat->save();

            $laporans[] = $laporan;
        }

        // Auto Validate Jika Diaktifkan
        $autoValidate = AutoValidate::first();
        foreach ($laporans as $laporan) {
            if (($autoValidate && $autoValidate->penggunaan) || ($laporan->alat && $laporan->alat->auto_validate == '1')) {
                $laporan->status_peminjaman = 'Diterima';
                $laporan->status_pengembalian = 'Belum Dikembalikan';
                $laporan->updated_at = now();
                $laporan->save();
            }
        }

        if (count($laporans) === 0) {
            return redirect()->route('client.riwayat-penggunaan')->with('warning', 'Tidak ada alat yang berhasil diajukan (mungkin sudah digunakan sebelumnya).');
        }

        return redirect()->route('client.riwayat-penggunaan')->with('message', 'Penggunaan alat berhasil disimpan.');
    }

    public function indexRuangan()
    {
        $ruangans = Ruangan::where('status', 'Tersedia')->get();
        $now = now();
        $jadwalBooking = 
            \App\Models\Laporan::where('status_peminjaman', 'Diterima')
            ->where('waktu_selesai', '>=', $now)
            ->with(['user', 'ruangan'])
            ->orderBy('waktu_mulai')
            ->get();
        return view("client.penggunaan-ruangan.index", compact('ruangans', 'jadwalBooking'));
    }

    public function storeRuangan(LaporanRequest $request)
    {
        $ruanganId = $request->input('ruangan_id');
        $waktuMulai = \Carbon\Carbon::parse($request->input('waktu_mulai'));
        $waktuSelesai = \Carbon\Carbon::parse($request->input('waktu_selesai'));

        // Validasi overlap booking
        $overlap = \App\Models\Laporan::where('ruangan_id', $ruanganId)
            ->whereIn('status_peminjaman', ['Menunggu', 'Diterima'])
            ->where(function($query) use ($waktuMulai, $waktuSelesai) {
                $query->where(function($q) use ($waktuMulai, $waktuSelesai) {
                    $q->where('waktu_mulai', '<', $waktuSelesai)
                      ->where('waktu_selesai', '>', $waktuMulai);
                });
            })
            ->with('user')
            ->first();

        if ($overlap) {
            $bookedBy = $overlap->user ? $overlap->user->name : 'User lain';
            $bookedStart = \Carbon\Carbon::parse($overlap->waktu_mulai)->format('d-m-Y H:i');
            $bookedEnd = \Carbon\Carbon::parse($overlap->waktu_selesai)->format('d-m-Y H:i');
            return redirect()->back()->withInput()->withErrors([
                'ruangan_id' => "Ruangan sudah dibooking oleh $bookedBy pada $bookedStart sampai $bookedEnd. Silakan pilih waktu berbeda."
            ]);
        }

        $laporan = Laporan::create([
            'user_id'           => Auth::id(),
            'ruangan_id'        => $ruanganId,
            'waktu_mulai'    => $waktuMulai,
            'waktu_selesai'  => $waktuSelesai,
            'tujuan_penggunaan' => $request->input('tujuan_penggunaan'),
            'status_peminjaman' => 'Menunggu',
            'tipe'              => 'ruangan',
        ]);

        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/surat', $filename);
            $laporan->surat = $filename;
            $laporan->save();
        }

        // Auto validate if enabled
        $autoValidate = AutoValidate::first();
        if ($autoValidate && $autoValidate->penggunaan || $laporan->ruangan->auto_validate == '1') {
            $laporan->status_peminjaman = 'Diterima';
            $laporan->status_pengembalian = 'Belum Dikembalikan';
            $laporan->updated_at = now();
            $laporan->save();
        }

        return redirect()->route('client.riwayat-penggunaan')->with('message', 'Penggunaan ruangan berhasil disimpan.');
    }

    public function kembalikanAlat($alatId)
    {
        $userId = Auth::id();

        // Cari laporan peminjaman yang aktif dan belum dikembalikan
        $laporan = Laporan::where('user_id', $userId)
            ->where('alat_id', $alatId)
            ->where('status_peminjaman', 'Diterima')
            ->where(function($query) {
                $query->where('status_pengembalian', 'Belum Dikembalikan')
                      ->orWhereNull('status_pengembalian');
            })
            ->latest()
            ->first();

        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan penggunaan tidak ditemukan atau sudah dikembalikan.']);
        }

        // Update status pengembalian - masih perlu validasi admin
        $laporan->tgl_pengembalian = now();
        $laporan->status_pengembalian = 'Belum Dikembalikan'; // Tetap 'Belum Dikembalikan' agar admin bisa validasi
        $laporan->save();

        // Status alat belum diubah - tunggu validasi admin
        // $alat = Alat::find($alatId);
        // if ($alat) {
        //     $alat->status = 'Tersedia';
        //     $alat->save();
        // }

        return response()->json(['success' => true, 'message' => 'Alat berhasil dikembalikan dan menunggu validasi admin.']);
    }

    public function ajaxJadwalBookingRuangan($ruanganId)
    {
        $now = now();
        $jadwalBooking = \App\Models\Laporan::where('ruangan_id', $ruanganId)
            ->where('status_peminjaman', 'Diterima')
            ->where('waktu_selesai', '>=', $now)
            ->with('user')
            ->orderBy('waktu_mulai')
            ->get();
        $data = $jadwalBooking->map(function($item) {
            return [
                'nama' => $item->user->name ?? '-',
                'tujuan' => $item->tujuan_penggunaan ?? '-',
                'waktu_mulai' => \Carbon\Carbon::parse($item->waktu_mulai)->format('d-m-Y H:i'),
                'waktu_selesai' => \Carbon\Carbon::parse($item->waktu_selesai)->format('d-m-Y H:i'),
                'status' => $item->status_peminjaman,
            ];
        });
        return response()->json($data);
    }
}
