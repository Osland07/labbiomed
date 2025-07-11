<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Bahan;
use Illuminate\Http\Request;
use App\Http\Requests\BahanRequest;
use App\Models\BahanLog;
use Illuminate\Support\Facades\Auth;

class AdminBahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-bahan')->only(['index']);
        $this->middleware('permission:create-bahan')->only(['store']);
        $this->middleware('permission:edit-bahan')->only(['update']);
        $this->middleware('permission:delete-bahan')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $categories = Category::where('type', 'bahan')->get();

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        if ($search) {
            $bahans = Bahan::where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $bahans = Bahan::paginate($validPerPage);
        }

        $stokRendah = Bahan::whereColumn('stock', '<=', 'min_stock')->get();
        $allBahans = Bahan::all();

        return view("admin.bahan.index", compact('bahans', 'stokRendah', 'categories', 'search', 'perPage', 'allBahans'));
    }

    public function store(BahanRequest $request)
    {
        $bahan = Bahan::create($request->validated());

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $file_name = $bahan->name . '_' . time() . '.' . $img->getClientOriginalExtension();
            $bahan->img = $file_name;
            $bahan->update();
            $img->storeAs('public', $file_name);
        }

        return back()->with('message', 'Berhasil Tambah Data Bahan!');
    }

    public function update(BahanRequest $request, $id)
    {
        $bahan = Bahan::findOrFail($id);
        $bahan->update($request->validated());

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $file_name = $bahan->name . '_' . time() . '.' . $img->getClientOriginalExtension();
            $bahan->img = $file_name;
            $bahan->update();
            $img->storeAs('public', $file_name);
        }
        
        return back()->with('message', 'Berhasil Edit Data Bahan!');
    }

    public function destroy($id)
    {
        Bahan::findOrFail($id)->forceDelete();
        return back()->with('message', 'Berhasil Hapus Data Bahan!');
    }

    public function bahanMasuk(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $bahan = Bahan::findOrFail($request->bahan_id);
        $bahan->stock += $request->jumlah;
        $bahan->save();
        BahanLog::create([
            'bahan_id' => $bahan->id,
            'user_id' => Auth::id(),
            'tipe' => 'masuk',
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);
        return back()->with('message', 'Stok bahan berhasil ditambah.');
    }

    public function bahanKeluar(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $bahan = Bahan::findOrFail($request->bahan_id);
        if ($request->jumlah > $bahan->stock) {
            return back()->withErrors(['jumlah' => 'Jumlah keluar melebihi stok tersedia.'])->withInput();
        }
        $bahan->stock -= $request->jumlah;
        $bahan->save();
        BahanLog::create([
            'bahan_id' => $bahan->id,
            'user_id' => Auth::id(),
            'tipe' => 'keluar',
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);
        return back()->with('message', 'Stok bahan berhasil dikurangi.');
    }
}
