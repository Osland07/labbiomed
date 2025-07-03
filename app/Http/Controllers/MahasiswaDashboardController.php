<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use App\Models\PenggunaanBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data untuk mahasiswa
        $pengajuanPending = LaporanPeminjaman::where('user_id', $user->id)
            ->whereIn('status_validasi', ['Menunggu Laboran', 'Menunggu Koordinator'])
            ->count();

        $pengajuanDisetujui = LaporanPeminjaman::where('user_id', $user->id)
            ->where('status_validasi', 'Diterima')
            ->count();

        $pengajuanSelesai = LaporanPeminjaman::where('user_id', $user->id)
            ->where('status_validasi', 'Selesai')
            ->count();

        $pengajuanDitolak = LaporanPeminjaman::where('user_id', $user->id)
            ->where('status_validasi', 'Ditolak')
            ->count();

        // Kunjungan hari ini
        $kunjunganHariIni = Kunjungan::where('user_id', $user->id)
            ->whereDate('waktu_masuk', today())
            ->count();

        // Penggunaan alat hari ini
        $penggunaanAlatHariIni = Laporan::where('user_id', $user->id)
            ->where('alat_id', '!=', null)
            ->whereDate('waktu_mulai', today())
            ->count();

        // Penggunaan ruangan hari ini
        $penggunaanRuanganHariIni = Laporan::where('user_id', $user->id)
            ->where('ruangan_id', '!=', null)
            ->whereDate('waktu_mulai', today())
            ->count();

        // Penggunaan bahan hari ini
        $penggunaanBahanHariIni = PenggunaanBahan::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        // Total kunjungan bulan ini
        $totalKunjunganBulanIni = Kunjungan::where('user_id', $user->id)
            ->whereMonth('waktu_masuk', now()->month)
            ->whereYear('waktu_masuk', now()->year)
            ->count();

        // Pengajuan terbaru
        $pengajuanTerbaru = LaporanPeminjaman::where('user_id', $user->id)
            ->with(['dosen', 'alatList'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Aktivitas terbaru (kunjungan, penggunaan alat, penggunaan ruangan)
        $aktivitasTerbaru = collect();
        
        // Tambahkan kunjungan
        $kunjungan = Kunjungan::where('user_id', $user->id)
            ->with('ruangan')
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
        $penggunaanAlat = Laporan::where('user_id', $user->id)
            ->where('alat_id', '!=', null)
            ->with('alat')
            ->orderBy('waktu_mulai', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                $item->jenis_aktivitas = 'Penggunaan Alat';
                $item->waktu = $item->waktu_mulai;
                return $item;
            });
        $aktivitasTerbaru = $aktivitasTerbaru->merge($penggunaanAlat);

        // Tambahkan penggunaan ruangan
        $penggunaanRuangan = Laporan::where('user_id', $user->id)
            ->where('ruangan_id', '!=', null)
            ->with('ruangan')
            ->orderBy('waktu_mulai', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                $item->jenis_aktivitas = 'Penggunaan Ruangan';
                $item->waktu = $item->waktu_mulai;
                return $item;
            });
        $aktivitasTerbaru = $aktivitasTerbaru->merge($penggunaanRuangan);

        // Urutkan berdasarkan waktu dan ambil 5 terbaru
        $aktivitasTerbaru = $aktivitasTerbaru->sortByDesc('waktu')->take(5);

        return view('mahasiswa.dashboard', compact(
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanSelesai',
            'pengajuanDitolak',
            'kunjunganHariIni',
            'penggunaanAlatHariIni',
            'penggunaanRuanganHariIni',
            'penggunaanBahanHariIni',
            'totalKunjunganBulanIni',
            'pengajuanTerbaru',
            'aktivitasTerbaru'
        ));
    }
} 