<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use App\Models\PenggunaanBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data untuk dosen
        $mahasiswaBimbingan = User::role('Mahasiswa')
            ->whereIn('id', function ($query) use ($user) {
                $query->select('user_id')
                    ->from('laporan_peminjamans')
                    ->where('dosen_id', $user->id)
                    ->where('status_validasi', '!=', 'Ditolak');
            })
            ->count();

        $pengajuanPending = LaporanPeminjaman::where('dosen_id', $user->id)
            ->whereIn('status_validasi', ['Menunggu Laboran', 'Menunggu Koordinator'])
            ->count();

        $pengajuanDisetujui = LaporanPeminjaman::where('dosen_id', $user->id)
            ->where('status_validasi', 'Diterima')
            ->count();

        $pengajuanSelesai = LaporanPeminjaman::where('dosen_id', $user->id)
            ->where('status_validasi', 'Selesai')
            ->count();

        // Aktivitas mahasiswa bimbingan hari ini
        $aktivitasHariIni = Kunjungan::whereIn('user_id', function ($query) use ($user) {
                $query->select('user_id')
                    ->from('laporan_peminjamans')
                    ->where('dosen_id', $user->id);
            })
            ->whereDate('waktu_masuk', today())
            ->count();

        // Penggunaan bahan oleh mahasiswa bimbingan
        $penggunaanBahan = PenggunaanBahan::whereIn('user_id', function ($query) use ($user) {
                $query->select('user_id')
                    ->from('laporan_peminjamans')
                    ->where('dosen_id', $user->id);
            })
            ->whereDate('created_at', today())
            ->count();

        // Pengajuan terbaru yang perlu diperhatikan
        $pengajuanTerbaru = LaporanPeminjaman::where('dosen_id', $user->id)
            ->whereIn('status_validasi', ['Menunggu Laboran', 'Menunggu Koordinator'])
            ->with(['user', 'alatList'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'mahasiswaBimbingan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanSelesai',
            'aktivitasHariIni',
            'penggunaanBahan',
            'pengajuanTerbaru'
        ));
    }
} 