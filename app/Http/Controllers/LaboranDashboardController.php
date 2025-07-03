<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Ruangan;
use App\Models\PenggunaanBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboranDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data untuk laboran
        $totalAlat = Alat::count();
        $totalBahan = Bahan::count();
        $totalRuangan = Ruangan::count();
        
        // Alat yang perlu maintenance
        $alatPerluMaintenance = Alat::where('kondisi', 'Rusak')
            ->orWhere('kondisi', 'Maintenance')
            ->count();

        // Bahan yang stoknya menipis (kurang dari 10)
        $bahanStokMenipis = Bahan::where('stok', '<', 10)
            ->count();

        // Pengajuan yang menunggu validasi laboran
        $pengajuanMenungguLaboran = LaporanPeminjaman::where('status_validasi', 'Menunggu Laboran')
            ->count();

        // Pengajuan yang sudah divalidasi laboran
        $pengajuanDivalidasiLaboran = LaporanPeminjaman::where('validated_by_laboran', $user->id)
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

        // Penggunaan bahan hari ini
        $penggunaanBahanHariIni = PenggunaanBahan::whereDate('created_at', today())
            ->count();

        // Laporan kerusakan hari ini
        $laporanKerusakanHariIni = Laporan::where('kondisi_setelah', 'Rusak')
            ->whereDate('created_at', today())
            ->count();

        // Pengajuan terbaru yang menunggu validasi laboran
        $pengajuanTerbaru = LaporanPeminjaman::where('status_validasi', 'Menunggu Laboran')
            ->with(['user', 'dosen', 'alatList'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Alat yang perlu diperhatikan
        $alatPerluPerhatian = Alat::where('kondisi', 'Rusak')
            ->orWhere('kondisi', 'Maintenance')
            ->orWhere('kondisi', 'Kurang Baik')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Bahan yang stoknya menipis
        $bahanStokMenipisList = Bahan::where('stok', '<', 10)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Aktivitas terbaru (kunjungan, penggunaan, laporan kerusakan)
        $aktivitasTerbaru = collect();
        
        // Tambahkan kunjungan
        $kunjungan = Kunjungan::with('ruangan', 'user')
            ->orderBy('waktu_masuk', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                $item->jenis_aktivitas = 'Kunjungan';
                $item->waktu = $item->waktu_masuk;
                return $item;
            });
        $aktivitasTerbaru = $aktivitasTerbaru->merge($kunjungan);

        // Tambahkan penggunaan alat
        $penggunaanAlat = Laporan::where('alat_id', '!=', null)
            ->with('alat', 'user')
            ->orderBy('waktu_mulai', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                $item->jenis_aktivitas = 'Penggunaan Alat';
                $item->waktu = $item->waktu_mulai;
                return $item;
            });
        $aktivitasTerbaru = $aktivitasTerbaru->merge($penggunaanAlat);

        // Tambahkan laporan kerusakan
        $laporanKerusakan = Laporan::where('kondisi_setelah', 'Rusak')
            ->with('alat', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                $item->jenis_aktivitas = 'Laporan Kerusakan';
                $item->waktu = $item->created_at;
                return $item;
            });
        $aktivitasTerbaru = $aktivitasTerbaru->merge($laporanKerusakan);

        // Urutkan berdasarkan waktu dan ambil 5 terbaru
        $aktivitasTerbaru = $aktivitasTerbaru->sortByDesc('waktu')->take(5);

        return view('laboran.dashboard', compact(
            'totalAlat',
            'totalBahan',
            'totalRuangan',
            'alatPerluMaintenance',
            'bahanStokMenipis',
            'pengajuanMenungguLaboran',
            'pengajuanDivalidasiLaboran',
            'kunjunganHariIni',
            'penggunaanAlatHariIni',
            'penggunaanRuanganHariIni',
            'penggunaanBahanHariIni',
            'laporanKerusakanHariIni',
            'pengajuanTerbaru',
            'alatPerluPerhatian',
            'bahanStokMenipisList',
            'aktivitasTerbaru'
        ));
    }
} 