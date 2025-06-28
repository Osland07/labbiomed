<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KunjunganExport;

class AdminKunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunjungan::with('ruangan')->orderBy('waktu_masuk', 'desc');
        
        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim_nip', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('ruangan', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('waktu_masuk', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('waktu_masuk', '<=', $request->date_to);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('waktu_keluar');
            } elseif ($request->status === 'completed') {
                $query->whereNotNull('waktu_keluar');
            }
        }
        
        // Filter berdasarkan ruangan
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        
        $kunjungans = $query->paginate(20);
        
        // Statistik untuk dashboard
        $stats = [
            'total' => Kunjungan::count(),
            'today' => Kunjungan::whereDate('waktu_masuk', Carbon::today())->count(),
            'active' => Kunjungan::whereNull('waktu_keluar')->count(),
            'this_month' => Kunjungan::whereMonth('waktu_masuk', Carbon::now()->month)->count(),
            'this_week' => Kunjungan::whereBetween('waktu_masuk', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
        ];
        
        // Ruangan terpopuler
        $popularRoom = Kunjungan::selectRaw('ruangan_id, COUNT(*) as visit_count')
            ->groupBy('ruangan_id')
            ->orderBy('visit_count', 'desc')
            ->first();
            
        $popularRoomName = $popularRoom ? Ruangan::find($popularRoom->ruangan_id)->name : 'N/A';
        
        // Daftar ruangan untuk filter dan sidebar
        $ruangans = Ruangan::orderBy('name')->get();
        
        return view('admin.kunjungan.index', compact(
            'kunjungans', 
            'stats', 
            'popularRoomName', 
            'ruangans',
            'request'
        ));
    }
    
    public function export(Request $request)
    {
        return Excel::download(new KunjunganExport($request), 'Laporan Kunjungan Laboratorium.xlsx');
    }

    public function generateQR()
    {
        $ruangans = Ruangan::orderBy('name')->get();
        return view('admin.kunjungan.generate-qr', compact('ruangans'));
    }
}
