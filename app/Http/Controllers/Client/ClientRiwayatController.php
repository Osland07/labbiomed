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
        $this->middleware('permission:history-client')->only(['riwayatPengajuan', 'riwayatPenggunaan', 'kunjungan']);
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

        $query = LaporanPeminjaman::where('user_id', Auth::user()->id)
            ->whereIn('status_validasi', ['Diterima', 'Ditolak', 'Selesai'])
            ->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('judul_penelitian', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);
        
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

    public function kunjungan(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $query = \App\Models\Kunjungan::where('user_id', Auth::user()->id)
            ->with('ruangan')
            ->orderBy('waktu_masuk', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('ruangan', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $kunjungans = $query->paginate($validPerPage);

        return view('client.riwayat.kunjungan.index', compact('kunjungans', 'search', 'perPage'));
    }
}
