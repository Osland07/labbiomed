<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientRiwayatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:history-client')->only(['riwayatPengajuan', 'riwayatPenggunaan']);
    }

    public function riwayatPengajuan(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        if ($search) {
            $laporans = LaporanPeminjaman::where('user_id', Auth::user()->id)->whereIn('status_validasi', ['Diterima', 'Ditolak'])->orderBy('updated_at', 'desc')->where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $laporans = LaporanPeminjaman::where('user_id', Auth::user()->id)->whereIn('status_validasi', ['Diterima', 'Ditolak'])->orderBy('updated_at', 'desc')->paginate($validPerPage);
        }
        return view("client.riwayat.pengajuan.index", compact('laporans', 'search', 'perPage'));
    }

    public function riwayatPenggunaan(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        if ($search) {
            $laporans = Laporan::where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $laporans = Laporan::where('user_id', Auth::user()->id)->orderBy('updated_at', 'desc')->paginate($validPerPage);
        }

        return view("client.riwayat.penggunaan.index", compact('laporans', 'search', 'perPage'));
    }

    public function kunjungan()
    {
        $kunjungans = \App\Models\Kunjungan::where('nama', auth()->user()->name)
            ->orderBy('waktu_masuk', 'desc')
            ->paginate(20);
        return view('client.riwayat.kunjungan.index', compact('kunjungans'));
    }
}
