<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanPeminjaman;
use App\Models\Kunjungan;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:monitoring-mahasiswa');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);
        $filterStatus = $request->input('status', 'all');

        // Jika user adalah dosen, hanya tampilkan mahasiswa bimbingannya
        if ($user->hasRole('Dosen')) {
            $mahasiswaBimbingan = User::role('Mahasiswa')
                ->whereIn('id', function ($query) use ($user) {
                    $query->select('user_id')
                        ->from('laporan_peminjamans')
                        ->where('dosen_id', $user->id)
                        ->where('status_validasi', '!=', 'Ditolak');
                });
        } else {
            // Jika super admin, admin, atau koordinator, tampilkan semua mahasiswa
            $mahasiswaBimbingan = User::role('Mahasiswa');
        }

        // Filter mahasiswa berdasarkan search
        if ($search) {
            $mahasiswaBimbingan->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('nim', 'like', '%' . $search . '%');
            });
        }

        // Paginate mahasiswa
        $mahasiswaBimbingan = $mahasiswaBimbingan->paginate($perPage);

        return view('admin.monitoring.index', compact('mahasiswaBimbingan', 'search', 'perPage', 'filterStatus'));
    }

    public function detailMahasiswa($mahasiswaId, Request $request)
    {
        $user = Auth::user();
        $mahasiswa = User::findOrFail($mahasiswaId);

        // Cek apakah dosen berhak melihat mahasiswa ini
        if ($user->hasRole('Dosen')) {
            $isMahasiswaBimbingan = LaporanPeminjaman::where('dosen_id', $user->id)
                ->where('user_id', $mahasiswaId)
                ->exists();
            
            if (!$isMahasiswaBimbingan) {
                return redirect()->back()->with('error', 'Anda tidak berhak melihat aktivitas mahasiswa ini.');
            }
        }
        // Super admin, admin, dan koordinator bisa melihat semua mahasiswa

        $filterAktivitas = $request->input('aktivitas', 'all');
        $filterTanggal = $request->input('tanggal', 'all');

        // Ambil data aktivitas mahasiswa
        $kunjungan = Kunjungan::where('user_id', $mahasiswaId);
        $penggunaanAlat = Laporan::where('user_id', $mahasiswaId)->where('alat_id', '!=', null);
        $peminjaman = LaporanPeminjaman::where('user_id', $mahasiswaId);

        // Filter berdasarkan tanggal
        if ($filterTanggal === 'hari_ini') {
            $kunjungan->whereDate('waktu_masuk', today());
            $penggunaanAlat->whereDate('waktu_mulai', today());
            $peminjaman->whereDate('created_at', today());
        } elseif ($filterTanggal === 'minggu_ini') {
            $kunjungan->whereBetween('waktu_masuk', [now()->startOfWeek(), now()->endOfWeek()]);
            $penggunaanAlat->whereBetween('waktu_mulai', [now()->startOfWeek(), now()->endOfWeek()]);
            $peminjaman->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filterTanggal === 'bulan_ini') {
            $kunjungan->whereMonth('waktu_masuk', now()->month);
            $penggunaanAlat->whereMonth('waktu_mulai', now()->month);
            $peminjaman->whereMonth('created_at', now()->month);
        }

        $kunjungan = $kunjungan->orderBy('waktu_masuk', 'desc')->get();
        $penggunaanAlat = $penggunaanAlat->orderBy('waktu_mulai', 'desc')->get();
        $peminjaman = $peminjaman->orderBy('created_at', 'desc')->get();

        // Statistik aktivitas
        $statistik = [
            'total_kunjungan' => Kunjungan::where('user_id', $mahasiswaId)->count(),
            'total_penggunaan_alat' => Laporan::where('user_id', $mahasiswaId)->where('alat_id', '!=', null)->count(),
            'total_peminjaman' => LaporanPeminjaman::where('user_id', $mahasiswaId)->count(),
            'kunjungan_hari_ini' => Kunjungan::where('user_id', $mahasiswaId)->whereDate('waktu_masuk', today())->count(),
            'penggunaan_hari_ini' => Laporan::where('user_id', $mahasiswaId)->where('alat_id', '!=', null)->whereDate('waktu_mulai', today())->count(),
        ];

        return view('admin.monitoring.detail', compact('mahasiswa', 'kunjungan', 'penggunaanAlat', 'peminjaman', 'statistik', 'filterAktivitas', 'filterTanggal'));
    }

    public function laporanAktivitas(Request $request)
    {
        $user = Auth::user();
        $filterTanggal = $request->input('tanggal', 'bulan_ini');
        $filterMahasiswa = $request->input('mahasiswa', 'all');

        // Jika user adalah dosen, hanya tampilkan mahasiswa bimbingannya
        if ($user->hasRole('Dosen')) {
            $mahasiswaBimbingan = User::role('Mahasiswa')
                ->whereIn('id', function ($query) use ($user) {
                    $query->select('user_id')
                        ->from('laporan_peminjamans')
                        ->where('dosen_id', $user->id)
                        ->where('status_validasi', '!=', 'Ditolak');
                })
                ->get();
            $mahasiswaIds = $mahasiswaBimbingan->pluck('id')->toArray();
        } else {
            // Jika super admin, admin, atau koordinator, tampilkan semua mahasiswa
            $mahasiswaBimbingan = User::role('Mahasiswa')->get();
            $mahasiswaIds = $mahasiswaBimbingan->pluck('id')->toArray();
        }

        // Filter berdasarkan mahasiswa tertentu
        if ($filterMahasiswa !== 'all') {
            $mahasiswaIds = [$filterMahasiswa];
        }

        // Filter berdasarkan tanggal
        $startDate = null;
        $endDate = null;

        switch ($filterTanggal) {
            case 'hari_ini':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'minggu_ini':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'bulan_ini':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'semester_ini':
                $startDate = now()->month >= 8 ? now()->startOfYear()->addMonths(7) : now()->startOfYear();
                $endDate = now()->month >= 8 ? now()->endOfYear() : now()->startOfYear()->addMonths(6)->endOfMonth();
                break;
        }

        // Ambil data aktivitas
        $kunjungan = Kunjungan::whereIn('user_id', $mahasiswaIds);
        $penggunaanAlat = Laporan::whereIn('user_id', $mahasiswaIds)->where('alat_id', '!=', null);
        $peminjaman = LaporanPeminjaman::whereIn('user_id', $mahasiswaIds);

        if ($startDate && $endDate) {
            $kunjungan->whereBetween('waktu_masuk', [$startDate, $endDate]);
            $penggunaanAlat->whereBetween('waktu_mulai', [$startDate, $endDate]);
            $peminjaman->whereBetween('created_at', [$startDate, $endDate]);
        }

        $kunjungan = $kunjungan->with('user', 'ruangan')->get();
        $penggunaanAlat = $penggunaanAlat->with('user', 'alat')->get();
        $peminjaman = $peminjaman->with('user', 'dosen')->get();

        // Statistik ringkasan
        $statistik = [
            'total_mahasiswa' => count($mahasiswaIds),
            'total_kunjungan' => $kunjungan->count(),
            'total_penggunaan_alat' => $penggunaanAlat->count(),
            'total_peminjaman' => $peminjaman->count(),
            'mahasiswa_teraktif' => $this->getMahasiswaTeraktif($mahasiswaIds, $startDate, $endDate),
        ];

        return view('admin.monitoring.laporan', compact('kunjungan', 'penggunaanAlat', 'peminjaman', 'statistik', 'mahasiswaBimbingan', 'filterTanggal', 'filterMahasiswa'));
    }

    private function getMahasiswaTeraktif($mahasiswaIds, $startDate, $endDate)
    {
        $kunjunganCounts = Kunjungan::whereIn('user_id', $mahasiswaIds)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('waktu_masuk', [$startDate, $endDate]);
            })
            ->selectRaw('user_id, COUNT(*) as total_kunjungan')
            ->groupBy('user_id')
            ->orderBy('total_kunjungan', 'desc')
            ->first();

        if ($kunjunganCounts) {
            return User::find($kunjunganCounts->user_id);
        }

        return null;
    }
} 