<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Ruangan;
use App\Models\Laporan;
use App\Models\LaporanPeminjaman;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPeminjamanExport;
use App\Exports\LaporanPenggunaanExport;
use App\Exports\LaporanKerusakanExport;

class AdminLaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:peminjaman-laporan')->only(['laporanPeminjaman']);
        $this->middleware('permission:penggunaan-laporan')->only(['laporanPenggunaan']);
        $this->middleware('permission:kerusakan-laporan')->only(['laporanKerusakan']);
    }

    public function exportLaporanPeminjaman()
    {
        return Excel::download(new LaporanPeminjamanExport, 'Laporan Peminjaman.xlsx');
    }

    public function exportLaporanPenggunaan()
    {
        return Excel::download(new LaporanPenggunaanExport, 'Laporan Penggunaan.xlsx');
    }

    public function exportLaporanKerusakan()
    {
        return Excel::download(new LaporanKerusakanExport, 'Laporan Kerusakan.xlsx');
    }

    public function laporanPeminjaman(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        if ($search) {
            $laporans = LaporanPeminjaman::whereIn('status_validasi', ['Diterima', 'Ditolak'])->orderBy('updated_at', 'desc')->where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $laporans = LaporanPeminjaman::whereIn('status_validasi', ['Diterima', 'Ditolak'])->orderBy('updated_at', 'desc')->paginate($validPerPage);
        }

        return view("admin.laporan.peminjaman.index", compact('laporans', 'search', 'perPage'));
    }

    public function laporanPenggunaan(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
            'filter' => 'nullable|integer', // user_id
            'filter2' => 'nullable|string|max:255', // nama alat/bahan/ruangan
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);
        $userId = $request->input('filter');
        $itemFilter = $request->input('filter2');

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $laporans = Laporan::whereIn('status_peminjaman', ['Diterima', 'Ditolak'])
            ->orderBy('updated_at', 'desc')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when($itemFilter, function ($query) use ($itemFilter) {
                $query->where(function ($q) use ($itemFilter) {
                    $q->whereHas('alat', fn($a) => $a->where('name', 'like', "%{$itemFilter}%"))
                        ->orWhereHas('bahan', fn($b) => $b->where('name', 'like', "%{$itemFilter}%"))
                        ->orWhereHas('ruangan', fn($r) => $r->where('name', 'like', "%{$itemFilter}%"));
                });
            })
            ->paginate($validPerPage);

        $users = $laporans->getCollection()
            ->map(fn($laporan) => optional($laporan->user))
            ->filter(fn($user) => $user && $user->id)
            ->unique('id')
            ->sortBy('name')
            ->values();
        $items = $laporans->getCollection()
            ->map(function ($laporan) {
                return optional($laporan->alat)->name
                    ?? optional($laporan->bahan)->name
                    ?? optional($laporan->ruangan)->name;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view("admin.laporan.penggunaan.index", compact('laporans', 'search', 'perPage', 'users', 'items'));
    }

    public function laporanKerusakan(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        if ($search) {
            $laporans = Laporan::whereIn('status_peminjaman', ['Diterima', 'Ditolak'])->orderBy('updated_at', 'desc')->where('kondisi_setelah', 'Rusak')->where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $laporans = Laporan::whereIn('status_peminjaman', ['Diterima', 'Ditolak'])->orderBy('updated_at', 'desc')->where('kondisi_setelah', 'Rusak')->paginate($validPerPage);
        }

        return view("admin.laporan.kerusakan.index", compact('laporans', 'search', 'perPage'));
    }
}
