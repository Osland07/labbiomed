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

        $query = LaporanPeminjaman::whereIn('status_validasi', ['Diterima', 'Ditolak', 'Selesai'])
            ->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('judul_penelitian', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

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
            'filter_user' => 'nullable|integer',
            'filter_item' => 'nullable|string|max:255',
            'filter_status' => 'nullable|in:all,belum,sudah',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);
        $filterUser = $request->input('filter_user');
        $filterItem = $request->input('filter_item');
        $filterStatus = $request->input('filter_status', 'all');

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $laporans = Laporan::whereIn('status_peminjaman', ['Diterima', 'Ditolak'])
            ->where('kondisi_setelah', 'Rusak')
            ->orderBy('updated_at', 'desc')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($filterUser, function ($query) use ($filterUser) {
                $query->where('user_id', $filterUser);
            })
            ->when($filterItem, function ($query) use ($filterItem) {
                $query->where(function ($q) use ($filterItem) {
                    $q->whereHas('alat', fn($a) => $a->where('name', 'like', "%{$filterItem}%"))
                        ->orWhereHas('bahan', fn($b) => $b->where('name', 'like', "%{$filterItem}%"))
                        ->orWhereHas('ruangan', fn($r) => $r->where('name', 'like', "%{$filterItem}%"));
                });
            })
            ->when($filterStatus !== 'all', function ($query) use ($filterStatus) {
                if ($filterStatus === 'belum') {
                    $query->where('is_replaced', false);
                } elseif ($filterStatus === 'sudah') {
                    $query->where('is_replaced', true);
                }
            })
            ->paginate($validPerPage);

        $users = Laporan::whereIn('status_peminjaman', ['Diterima', 'Ditolak'])
            ->where('kondisi_setelah', 'Rusak')
            ->with('user')
            ->get()
            ->map(fn($laporan) => optional($laporan->user))
            ->filter(fn($user) => $user && $user->id)
            ->unique('id')
            ->sortBy('name')
            ->values();
        $items = Laporan::whereIn('status_peminjaman', ['Diterima', 'Ditolak'])
            ->where('kondisi_setelah', 'Rusak')
            ->when($filterUser, function ($query) use ($filterUser) {
                $query->where('user_id', $filterUser);
            })
            ->with(['alat', 'bahan', 'ruangan'])
            ->get()
            ->map(function ($laporan) {
                return optional($laporan->alat)->name
                    ?? optional($laporan->bahan)->name
                    ?? optional($laporan->ruangan)->name;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
        $statusOptions = [
            'all' => 'Semua',
            'belum' => 'Belum Diganti',
            'sudah' => 'Sudah Diganti',
        ];
        return view("admin.laporan.kerusakan.index", compact('laporans', 'search', 'perPage', 'users', 'items', 'filterUser', 'filterItem', 'filterStatus', 'statusOptions'));
    }

    public function konfirmasiPenggantianKerusakan(Request $request, $laporanId)
    {
        $laporan = \App\Models\Laporan::findOrFail($laporanId);
        if ($laporan->is_replaced) {
            return back()->with('message', 'Laporan sudah dikonfirmasi penggantiannya.');
        }
        $laporan->is_replaced = true;
        $laporan->replaced_at = now();
        $laporan->replaced_by = auth()->id();
        if ($request->has('replace_note')) {
            $laporan->replace_note = $request->replace_note;
        }
        if ($request->hasFile('replace_image')) {
            $path = $request->file('replace_image')->store('replace_image', 'public');
            $laporan->replace_image = $path;
        }
        $laporan->save();
        // Update status alat jika ada
        if ($laporan->alat) {
            $laporan->alat->status = 'Tersedia';
            $laporan->alat->condition = 'Baik';
            $laporan->alat->save();
        }
        return back()->with('message', 'Konfirmasi penggantian/perbaikan berhasil.');
    }
}
