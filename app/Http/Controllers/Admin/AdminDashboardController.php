<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanPeminjaman;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all()->count();
        $roles = Role::all()->count();
        $pengajuan = LaporanPeminjaman::all()->count();
        $penggunaan = Laporan::where('kondisi_setelah', 'Baik')->count();
        $kerusakan = Laporan::where('kondisi_setelah', 'Rusak')->count();

        return view('admin.dashboard', compact('users', 'roles', 'pengajuan', 'penggunaan', 'kerusakan'));
    }
}
