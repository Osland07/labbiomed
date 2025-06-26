<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ClientCekController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:check-client')->only(['index']);
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:12,60,120',
            'view' => 'nullable|in:compact,detail',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 12);
        $view = $request->input('view', 'compact');
        $page = $request->input('page', 1);

        $validPerPage = in_array($perPage, [12, 60, 120]) ? $perPage : 12;

        if ($view === 'detail') {
            $alats = Alat::when($search, fn($q) => $q->where('name', 'like', "%$search%"))
                ->paginate($validPerPage);
        } else {
            $allAlats = Alat::all();

            if ($search) {
                $allAlats = $allAlats->filter(fn($item) => stripos($item->name, $search) !== false);
            }

            $grouped = $allAlats->groupBy(function ($item) {
                return preg_replace('/\s+#\d+$/', '', $item->name);
            });

            $sliced = $grouped->forPage($page, $validPerPage);

            $alats = new LengthAwarePaginator(
                $sliced,
                $grouped->count(),
                $validPerPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        $bahans = Bahan::when($search, fn($q) => $q->where('name', 'like', "%$search%"))->paginate($validPerPage);
        $ruangans = Ruangan::when($search, fn($q) => $q->where('name', 'like', "%$search%"))->paginate($validPerPage);

        return view("client.cek.index", compact('alats', 'bahans', 'ruangans', 'search', 'perPage', 'view'));
    }
}
