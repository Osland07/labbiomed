<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        if ($search) {
            $laporans = Laporan::whereNotNull('ruangan_id')
                ->whereHas('ruangan')
                ->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate($validPerPage);
        } else {
            $laporans = Laporan::whereNotNull('ruangan_id')
                ->whereHas('ruangan')
                ->paginate($validPerPage);
        }


        return view('jadwal.index', compact('laporans', 'search', 'perPage'));
    }
}
