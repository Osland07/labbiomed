<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoorlabDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data untuk koordinator laboratorium
        $totalAlat = Alat::count();
        $totalBahan = Bahan::count();
        $totalRuangan = Ruangan::count();
        $totalMahasiswa = User::role('Mahasiswa')->count();
        $totalDosen = User::role('Dosen')->count();

        // Pengajuan yang menunggu validasi koordinator
        $pengajuanMenungguKoordinator = LaporanPeminjaman::where('status_validasi', 'Menunggu Koordinator')
            ->count();

        // Pengajuan yang sudah disetujui
        $pengajuanDisetujui = LaporanPeminjaman::where('status_validasi', 'Diterima')
            ->count();

        // Pengajuan yang ditolak
        $pengajuanDitolak = LaporanPeminjaman::where('status_validasi', 'Ditolak')
            ->count();

        // Kunjungan hari ini
        $kunjunganHariIni = Kunjungan::whereDate('waktu_masuk', today())
            ->count();

        // Penggunaan alat hari ini
        $penggunaanAlatHariIni = Laporan::where('alat_id', '!=', null)
            ->whereDate('waktu_mulai', today())
            ->count();

        // Penggunaan ruangan hari ini
        $penggunaanRuanganHariIni = Laporan::where('ruangan_id', '!=', null)
            ->whereDate('waktu_mulai', today())
            ->count();

        // Pengajuan terbaru yang menunggu validasi koordinator
        $pengajuanTerbaru = LaporanPeminjaman::where('status_validasi', 'Menunggu Koordinator')
            ->with(['user', 'dosen', 'alatList'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik per bulan (6 bulan terakhir)
        $statistikBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $statistikBulanan[] = [
                'bulan' => $bulan->format('M Y'),
                'pengajuan' => LaporanPeminjaman::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->count(),
                'kunjungan' => Kunjungan::whereYear('waktu_masuk', $bulan->year)
                    ->whereMonth('waktu_masuk', $bulan->month)
                    ->count(),
                'penggunaan' => Laporan::whereYear('waktu_mulai', $bulan->year)
                    ->whereMonth('waktu_mulai', $bulan->month)
                    ->count(),
            ];
        }

        return view('koorlab.dashboard', compact(
            'totalAlat',
            'totalBahan',
            'totalRuangan',
            'totalMahasiswa',
            'totalDosen',
            'pengajuanMenungguKoordinator',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'kunjunganHariIni',
            'penggunaanAlatHariIni',
            'penggunaanRuanganHariIni',
            'pengajuanTerbaru',
            'statistikBulanan'
        ));
    }
} 