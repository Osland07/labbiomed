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

        return view("client.penggunaan-alat.index", compact('alats'));
    }

    public function storeAlat(LaporanRequest $request)
    {
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
        $waktuMulai = Carbon::parse($request->input('waktu_mulai'));
        $waktuSelesai = Carbon::parse($request->input('waktu_selesai'));
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
        return view("client.penggunaan-ruangan.index", compact('ruangans'));
    }

    public function storeRuangan(LaporanRequest $request)
    {
        $laporan = Laporan::create([
            'user_id'           => Auth::id(),
            'ruangan_id'        => $request->input('ruangan_id'),
            'waktu_mulai'    => Carbon::parse($request->input('waktu_mulai')),
            'waktu_selesai'  => Carbon::parse($request->input('waktu_selesai')),
            'tujuan_penggunaan' => $request->input('tujuan_penggunaan'),
            'status_peminjaman' => 'Menunggu',
            'tipe'              => 'ruangan',
        ]);

        $ruangan = Ruangan::findOrFail($laporan->ruangan_id);
        $ruangan->status = 'Sedang Digunakan';
        $ruangan->save();

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
            ->where('status_pengembalian', 'Belum Dikembalikan')
            ->latest()
            ->first();

        if (!$laporan) {
            return response()->json(['success' => false, 'message' => 'Laporan penggunaan tidak ditemukan atau sudah dikembalikan.']);
        }

        $laporan->tgl_pengembalian = now();
        $laporan->save();

        $alat = Alat::find($alatId);
        if ($alat) {
            $alat->status = 'Tersedia';
            $alat->save();
        }

        return response()->json(['success' => true, 'message' => 'Alat berhasil dikembalikan.']);
    }
}
