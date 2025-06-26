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

        $allAccept = LaporanPeminjaman::where('user_id', Auth::user()->id)
            ->where('status_validasi', 'Diterima')
            ->whereDate('tgl_pengembalian', '>=', $today)
            ->get();

        $alatIds = collect($allAccept->pluck('alat_id'))
            ->flatten()
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $alats = Alat::whereIn('id', $alatIds)->get();

        return view("client.penggunaan-alat.index", compact('alats'));
    }

    public function storeAlat(LaporanRequest $request)
    {
        $alatIds = $request->input('alat_id');
        if (!is_array($alatIds)) {
            $alatIds = [$alatIds];
        }

        foreach ($alatIds as $alatId) {
            $waktuMulai = Carbon::parse($request->input('waktu_mulai'));
            $waktuSelesai = Carbon::parse($request->input('waktu_selesai'));
            $tujuan = $request->input('tujuan_penggunaan');

            // Cek jika laporan identik sudah ada
            $existing = Laporan::where('user_id', Auth::id())
                ->where('alat_id', $alatId)
                ->where('waktu_mulai', $waktuMulai)
                ->where('waktu_selesai', $waktuSelesai)
                ->where('tujuan_penggunaan', $tujuan)
                ->whereIn('status_peminjaman', ['Menunggu', 'Diterima'])
                ->first();

            if ($existing) {
                continue;
            }

            $laporan = Laporan::create([
                'user_id'           => Auth::id(),
                'alat_id'           => $alatId,
                'waktu_mulai'    => Carbon::parse($request->input('waktu_mulai')),
                'waktu_selesai'  => Carbon::parse($request->input('waktu_selesai')),
                'tujuan_penggunaan' => $request->input('tujuan_penggunaan'),
                'status_peminjaman' => 'Menunggu',
                'tipe'              => 'alat',
            ]);

            $alat = Alat::findOrFail($laporan->alat_id);
            $alat->status = 'Sedang Digunakan';
            $alat->save();
        }


        // Auto validate if enabled
        $autoValidate = AutoValidate::first();
        if ($autoValidate && $autoValidate->penggunaan || $laporan->alat->auto_validate == '1') {
            $laporan->status_peminjaman = 'Diterima';
            $laporan->status_pengembalian = 'Belum Dikembalikan';
            $laporan->updated_at = now();
            $laporan->save();
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
        $alat = Alat::findOrFail($alatId);
        $laporans = Laporan::where('alat_id', $alatId)
            ->where('user_id', Auth::id())
            ->where('status_pengembalian', '!=', 'Sudah Dikembalikan')
            ->get();

        if ($alat && $laporans->count()) {
            $alat->status = 'Tersedia';
            $alat->save();
            foreach ($laporans as $laporan) {
                $laporan->status_pengembalian = 'Sudah Dikembalikan';
                $laporan->save();
            }
            return response()->json(['success' => true, 'message' => 'Alat berhasil dikembalikan.']);
        }
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengembalikan alat. Tidak ditemukan laporan aktif untuk alat ini.',
            'debug' => [
                'alat_id' => $alatId,
                'user_id' => Auth::id(),
                'laporans_count' => $laporans->count(),
            ]
        ], 400);
    }
}
