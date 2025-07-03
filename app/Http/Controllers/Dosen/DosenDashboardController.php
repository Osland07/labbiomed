<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use App\Models\PenggunaanBahan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data pengajuan yang diajukan oleh mahasiswa yang dibimbing
        $pengajuanPending = LaporanPeminjaman::where('dosen_id', $user->id)
            ->where('status_validasi', 'Menunggu Laboran')
            ->count();
            
        $pengajuanDisetujui = LaporanPeminjaman::where('dosen_id', $user->id)
            ->where('status_validasi', 'Diterima')
            ->count();
            
        $pengajuanSelesai = LaporanPeminjaman::where('dosen_id', $user->id)
            ->where('status_validasi', 'Selesai')
            ->count();
            
        $pengajuanDitolak = LaporanPeminjaman::where('dosen_id', $user->id)
            ->where('status_validasi', 'Ditolak')
            ->count();

        // Aktivitas hari ini
        $kunjunganHariIni = Kunjungan::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        $penggunaanAlatHariIni = Laporan::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        $penggunaanRuanganHariIni = Laporan::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();
            
        $penggunaanBahanHariIni = PenggunaanBahan::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Statistik bulan ini
        $totalKunjunganBulanIni = Kunjungan::where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Pengajuan terbaru yang dibimbing
        $pengajuanTerbaru = LaporanPeminjaman::where('dosen_id', $user->id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.dosen', compact(
            'pengajuanPending',
            'pengajuanDisetujui', 
            'pengajuanSelesai',
            'pengajuanDitolak',
            'kunjunganHariIni',
            'penggunaanAlatHariIni',
            'penggunaanRuanganHariIni',
            'penggunaanBahanHariIni',
            'totalKunjunganBulanIni',
            'pengajuanTerbaru'
        ));
    }
} 