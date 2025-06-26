<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-user')->only(['index']);
        $this->middleware('permission:create-user')->only(['store']);
        $this->middleware('permission:edit-user')->only(['update']);
        $this->middleware('permission:delete-user')->only(['destroy']);
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

        $roles = Role::all();

        if ($search) {
            $users = User::where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $users = User::paginate($validPerPage);
        }

        $counter = ($users->currentPage() - 1) * $users->perPage() + 1;

        return view("admin.user.index", compact('users', 'roles', 'counter', 'search', 'perPage'));
    }

    public function store(UserStoreRequest $request)
    {
        $userData = $request->validated();

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        }

        $role = $userData['role'];
        unset($userData['role']);

        $user = User::create($userData);

        $user->assignRole($role);

        return back()->with('message', 'Berhasil Tambah User!');
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $userData = $request->validated();

        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }

        $role = $userData['role'];
        unset($userData['role']);

        $user->update($userData);

        $user->syncRoles($role);

        return back()->with('message', 'Berhasil Edit User!');
    }


    public function destroy(string $id)
    {
        User::findOrFail($id)->forceDelete();
        return back()->with('message', 'Berhasil Hapus User!');
    }
}
