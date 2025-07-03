<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenggunaanBahan;
use App\Models\Bahan;
use Illuminate\Support\Facades\Auth;

class ClientPenggunaanBahanController extends Controller
{
    public function index()
    {
        $pengajuan = PenggunaanBahan::with('bahan')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
        $bahans = Bahan::where('stock', '>', 0)->get();
        return view('client.penggunaan-bahan.index', compact('pengajuan', 'bahans'));
    }

    public function create()
    {
        $bahans = Bahan::where('stock', '>', 0)->get();
        return view('client.penggunaan-bahan.create', compact('bahans'));
    }

    public function store(Request $request)
    {
        $bahan = Bahan::findOrFail($request->bahan_id);
        $satuan = strtolower($bahan->unit);
        $jumlahRule = ($satuan === 'pcs' || $satuan === 'unit') ? 'required|integer|min:1' : 'required|numeric|min:0.01';
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah' => $jumlahRule,
            'tujuan' => 'nullable|string|max:255',
        ]);
        if ($request->jumlah > $bahan->stock) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok tersedia.'])->withInput();
        }
        PenggunaanBahan::create([
            'user_id' => Auth::id(),
            'bahan_id' => $request->bahan_id,
            'jumlah' => $request->jumlah,
            'tujuan' => $request->tujuan,
            'status' => 'pending',
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('client.penggunaan-bahan')->with('message', 'Pengajuan penggunaan bahan berhasil diajukan.');
    }
} 