<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Alat;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Http\Requests\AlatRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminAlatController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-alat')->only(['index']);
        $this->middleware('permission:create-alat')->only(['store']);
        $this->middleware('permission:edit-alat')->only(['update']);
        $this->middleware('permission:delete-alat')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
            'view' => 'nullable|in:compact,detail',
            'status' => 'nullable|string|in:Tersedia,Sedang Digunakan,Maintance',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);
        $view = $request->input('view', 'compact');
        $status = $request->input('status');
        $page = $request->input('page', 1);
        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $categories = Category::where('type', 'alat')->get();
        $locations = Ruangan::all();

        $sources = [
            ['name' => 'Modal', 'id' => 'Modal'],
            ['name' => 'Hibah', 'id' => 'Hibah'],
            ['name' => 'Fakultas', 'id' => 'Fakultas'],
        ];

        if ($view === 'detail') {
            $alats = Alat::with('category')
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%");
                })
                ->when($status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->paginate($validPerPage);

            return view('admin.alat.index', compact('alats', 'categories', 'sources', 'locations', 'search', 'perPage', 'view', 'status'));
        }

        $allAlats = Alat::with('category')->get();

        if ($search) {
            $allAlats = $allAlats->filter(function ($item) use ($search) {
                return stripos($item->name, $search) !== false;
            });
        }

        if ($status) {
            $allAlats = $allAlats->where('status', $status);
        }

        $alat_groups = $allAlats->groupBy(function ($item) {
            $normalizedName = preg_replace('/\s+#\d+$/', '', $item->name);
            return $normalizedName;
        });

        $sliced = $alat_groups->forPage($page, $validPerPage);
        $alat_groups_paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $sliced,
            $alat_groups->count(),
            $validPerPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.alat.index', [
            'alat_groups' => $alat_groups_paginated,
            'locations' => $locations,
            'categories' => $categories,
            'sources' => $sources,
            'search' => $search,
            'perPage' => $perPage,
            'view' => $view,
            'status' => $status,
        ]);
    }

    public function store(AlatRequest $request)
    {
        $validated = $request->validated();
        $qty = (int) $request->input('qty', 1);

        $created = [];
        $prefix = strtoupper(Str::substr($validated['name'], 0, 2));
        $year = Carbon::now()->year;
        $series = Alat::max('id') + 100;

        $img = $request->file('img');
        $timestamp = time();

        for ($i = 1; $i <= $qty; $i++) {
            $serial_number = "{$prefix}-{$year}-{$series}-{$i}";

            $file_name = null;
            if ($img) {
                $file_name = Str::slug($validated['name']) . "_{$timestamp}_{$i}." . $img->getClientOriginalExtension();
                $img->storeAs('public', $file_name);
            }

            $alat = Alat::create([
                'name' => $validated['name'] . " #{$i}",
                'desc' => $validated['desc'] ?? null,
                'category_id' => $validated['category_id'],
                'location' => $validated['location'],
                'detail_location' => $validated['detail_location'] ?? null,
                'date_received' => $validated['date_received'] ?? null,
                'source' => $validated['source'] ?? null,
                'condition' => 'Baik',
                'status' => 'Tersedia',
                'serial_number' => $serial_number,
                'img' => $file_name,
            ]);

            $created[] = $alat;
        }

        return back()->with('message', count($created) . ' Data Alat berhasil ditambahkan!');
    }

    public function update(AlatRequest $request, $id)
    {
        $alat = Alat::findOrFail($id);
        $alat->update($request->validated());

        if ($request->hasFile('img')) {
            if ($alat->img && Storage::exists("public/{$alat->img}")) {
                Storage::delete("public/{$alat->img}");
            }

            $img = $request->file('img');
            $file_name = Str::slug($alat->name) . '_' . time() . '.' . $img->getClientOriginalExtension();
            $img->storeAs('public', $file_name);

            $alat->update(['img' => $file_name]);
        }

        return back()->with('message', 'Berhasil Edit Data Alat!');
    }

    public function destroy($id)
    {
        Alat::findOrFail($id)->forceDelete();
        return back()->with('message', 'Berhasil Hapus Data Alat!');
    }
}
