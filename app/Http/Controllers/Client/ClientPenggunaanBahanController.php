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
        // Validasi input dasar
        $request->validate([
            'tujuan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'bahans' => 'required|array|min:1',
            'bahans.*.bahan_id' => 'required|exists:bahans,id',
            'bahans.*.jumlah' => 'required|numeric|min:0.01',
        ], [
            'tujuan.required' => 'Tujuan penggunaan harus diisi.',
            'bahans.required' => 'Minimal harus ada 1 bahan yang dipilih.',
            'bahans.min' => 'Minimal harus ada 1 bahan yang dipilih.',
            'bahans.*.bahan_id.required' => 'Bahan harus dipilih.',
            'bahans.*.bahan_id.exists' => 'Bahan yang dipilih tidak valid.',
            'bahans.*.jumlah.required' => 'Jumlah harus diisi.',
            'bahans.*.jumlah.numeric' => 'Jumlah harus berupa angka.',
            'bahans.*.jumlah.min' => 'Jumlah minimal 0.01.',
        ]);

        $bahansData = $request->input('bahans');
        $createdPenggunaan = [];
        $errors = [];

        // Validasi setiap bahan dan cek stok
        foreach ($bahansData as $index => $bahanData) {
            $bahan = Bahan::findOrFail($bahanData['bahan_id']);
            $satuan = strtolower($bahan->unit);
            $jumlah = floatval($bahanData['jumlah']);

            // Validasi tipe data berdasarkan satuan
            if ($satuan === 'pcs' || $satuan === 'unit') {
                if ($jumlah != intval($jumlah)) {
                    $errors["bahans.{$index}.jumlah"] = "Jumlah untuk {$bahan->name} harus berupa bilangan bulat.";
                    continue;
                }
                $jumlah = intval($jumlah);
                if ($jumlah < 1) {
                    $errors["bahans.{$index}.jumlah"] = "Jumlah untuk {$bahan->name} minimal 1.";
                    continue;
                }
            } else {
                if ($jumlah < 0.01) {
                    $errors["bahans.{$index}.jumlah"] = "Jumlah untuk {$bahan->name} minimal 0.01.";
                    continue;
                }
            }

            // Validasi stok
            if ($jumlah > $bahan->stock) {
                $errors["bahans.{$index}.jumlah"] = "Jumlah {$bahan->name} melebihi stok tersedia ({$bahan->stock} {$bahan->unit}).";
                continue;
            }

            // Simpan data valid untuk diproses
            $createdPenggunaan[] = [
                'bahan' => $bahan,
                'jumlah' => $jumlah
            ];
        }

        // Jika ada error, kembalikan dengan pesan error
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Cek duplikasi bahan
        $bahanIds = array_column($bahansData, 'bahan_id');
        if (count($bahanIds) !== count(array_unique($bahanIds))) {
            return back()->withErrors(['bahans' => 'Tidak boleh memilih bahan yang sama lebih dari sekali.'])->withInput();
        }

        // Proses penyimpanan
        $userId = Auth::id();
        $tujuan = $request->input('tujuan');
        $keterangan = $request->input('keterangan');
        $totalCreated = 0;

        foreach ($createdPenggunaan as $item) {
            PenggunaanBahan::create([
                'user_id' => $userId,
                'bahan_id' => $item['bahan']->id,
                'jumlah' => $item['jumlah'],
                'tujuan' => $tujuan,
                'status' => 'pending',
                'keterangan' => $keterangan,
            ]);
            $totalCreated++;
        }

        $message = $totalCreated === 1 
            ? 'Pengajuan penggunaan bahan berhasil diajukan.'
            : "Pengajuan penggunaan {$totalCreated} bahan berhasil diajukan.";

        return redirect()->route('client.penggunaan-bahan')->with('message', $message);
    }
} 