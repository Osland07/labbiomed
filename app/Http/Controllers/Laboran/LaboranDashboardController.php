<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use App\Models\PenggunaanBahan;
use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Ruangan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaboranDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data pengajuan yang perlu divalidasi laboran
        $pengajuanPending = LaporanPeminjaman::where('status_validasi', 'Menunggu Laboran')
            ->count();
            
        $pengajuanDisetujui = LaporanPeminjaman::where('status_validasi', 'Diterima')
            ->count();
            
        $pengajuanSelesai = LaporanPeminjaman::where('status_validasi', 'Selesai')
            ->count();
            
        $pengajuanDitolak = LaporanPeminjaman::where('status_validasi', 'Ditolak')
            ->count();

        // Statistik alat dan bahan
        $totalAlat = Alat::count();
        $totalBahan = Bahan::count();
        $totalRuangan = Ruangan::count();

        // Aktivitas hari ini
        $kunjunganHariIni = Kunjungan::whereDate('created_at', Carbon::today())
            ->count();
            
        $penggunaanAlatHariIni = Laporan::whereDate('created_at', Carbon::today())
            ->count();
            
        $penggunaanRuanganHariIni = Laporan::whereDate('created_at', Carbon::today())
            ->count();
            
        $penggunaanBahanHariIni = PenggunaanBahan::whereDate('created_at', Carbon::today())
            ->count();

        // Statistik bulan ini
        $totalKunjunganBulanIni = Kunjungan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Pengajuan terbaru yang perlu divalidasi
        $pengajuanTerbaru = LaporanPeminjaman::where('status_validasi', 'Menunggu Laboran')
            ->with(['user', 'dosen'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.laboran', compact(
            'pengajuanPending',
            'pengajuanDisetujui', 
            'pengajuanSelesai',
            'pengajuanDitolak',
            'totalAlat',
            'totalBahan',
            'totalRuangan',
            'kunjunganHariIni',
            'penggunaanAlatHariIni',
            'penggunaanRuanganHariIni',
            'penggunaanBahanHariIni',
            'totalKunjunganBulanIni',
            'pengajuanTerbaru'
        ));
    }
} 