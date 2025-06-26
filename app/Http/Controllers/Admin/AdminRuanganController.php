<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Http\Requests\RuanganRequest;
use App\Models\Category;

class AdminRuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-ruangan')->only(['index']);
        $this->middleware('permission:create-ruangan')->only(['store']);
        $this->middleware('permission:edit-ruangan')->only(['update']);
        $this->middleware('permission:delete-ruangan')->only(['destroy']);
    }

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
            $ruangans = Ruangan::where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $ruangans = Ruangan::paginate($validPerPage);
        }

        return view("admin.ruangan.index", compact('ruangans', 'search', 'perPage'));
    }

    public function store(RuanganRequest $request)
    {
        $data = $request->validated();
        $data['category_id'] = $this->Category('Ruangan');

        $ruangan = Ruangan::create($data);

        if ($request->hasFile('foto_ruangan')) {
            $foto_ruangan = $request->file('foto_ruangan');
            $file_name = $ruangan->name . '_' . $foto_ruangan->getClientOriginalExtension();
            $ruangan->foto_ruangan = $file_name;
            $ruangan->update();
            $foto_ruangan->storeAs('public', $file_name);
        }
        
        if ($request->hasFile('foto_denah')) {
            $foto_denah = $request->file('foto_denah');
            $file_name = $ruangan->name . '_' . $foto_denah->getClientOriginalExtension();
            $ruangan->foto_denah = $file_name;
            $ruangan->update();
            $foto_denah->storeAs('public', $file_name);
        }

        return back()->with('message', 'Berhasil Tambah Data Ruangan!');
    }

    public function update(RuanganRequest $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $data = $request->validated();
        $data['category_id'] = $this->Category('Ruangan');

        $ruangan->update($data);

        if ($request->hasFile('foto_ruangan')) {
            $foto_ruangan = $request->file('foto_ruangan');
            $file_name = $ruangan->name . '_' . time() . '.' . $foto_ruangan->getClientOriginalExtension();
            $ruangan->foto_ruangan = $file_name;
            $ruangan->update();
            $foto_ruangan->storeAs('public', $file_name);
        }
        
        if ($request->hasFile('foto_denah')) {
            $foto_denah = $request->file('foto_denah');
            $file_name = $ruangan->name . '_' . time() . '.' . $foto_denah->getClientOriginalExtension();
            $ruangan->foto_denah = $file_name;
            $ruangan->update();
            $foto_denah->storeAs('public', $file_name);
        }
        
        return back()->with('message', 'Berhasil Edit Data Ruangan!');
    }

    public function destroy($id)
    {
        Ruangan::findOrFail($id)->forceDelete();
        return back()->with('message', 'Berhasil Hapus Data Ruangan!');
    }

    private function Category(string $name): string
    {
        $category = Category::where('name', $name)->first();
        if (!$category) {
            $category = Category::create([
                'name' => $name,
                'type' => 'ruangan',
            ]);
        }
        return $category->id;
    }
}
