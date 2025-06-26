<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class AdminCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-category')->only(['index']);
        $this->middleware('permission:create-category')->only(['store']);
        $this->middleware('permission:edit-category')->only(['update']);
        $this->middleware('permission:delete-category')->only(['destroy']);
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
            $categories = Category::where('name', 'like', "%{$search}%")->where('name', '!=', 'Ruangan')->paginate($validPerPage);
        } else {
            $categories = Category::where('name', '!=', 'Ruangan')->paginate($validPerPage);
        }

        return view("admin.category.index", compact('categories', 'search', 'perPage'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return back()->with('message', 'Berhasil Tambah Data Category!');
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return back()->with('message', 'Berhasil Edit Data Category!');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->forceDelete();
        return back()->with('message', 'Berhasil Hapus Data Category!');
    }
}
