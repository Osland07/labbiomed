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
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect berdasarkan role
        if ($user->hasRole('Dosen')) {
            return $this->dosenDashboard();
        } elseif ($user->hasRole('Koordinator Laboratorium')) {
            return $this->koorlabDashboard();
        } elseif ($user->hasRole('Mahasiswa')) {
            return $this->mahasiswaDashboard();
        } elseif ($user->hasRole('Laboran')) {
            return $this->laboranDashboard();
        } else {
            // Default dashboard untuk Super Admin dan Admin
            return $this->adminDashboard();
        }
    }

    private function dosenDashboard()
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

        return view('dashboard.dosen', compact(
            'mahasiswaBimbingan',
            'pengajuanPending',
            'pengajuanDisetujui',
            'pengajuanSelesai',
            'aktivitasHariIni',
            'penggunaanBahan',
            'pengajuanTerbaru'
        ));
    }

    private function koorlabDashboard()
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

        return view('dashboard.koorlab', compact(
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

    private function mahasiswaDashboard()
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

        return view('dashboard.mahasiswa', compact(
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

    private function laboranDashboard()
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

        return view('dashboard.laboran', compact(
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

    private function adminDashboard()
    {
        // Default dashboard untuk Super Admin dan Admin
        $users = User::all()->count();
        $roles = Role::all()->count();
        $pengajuan = LaporanPeminjaman::all()->count();
        $penggunaan = Laporan::where('kondisi_setelah', 'Baik')->count();
        $kerusakan = Laporan::where('kondisi_setelah', 'Rusak')->count();

        return view('dashboard.admin', compact('users', 'roles', 'pengajuan', 'penggunaan', 'kerusakan'));
    }
} 