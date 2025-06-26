<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $roles = Role::all();
        return view('profile.edit', ['user' => $request->user(), 'roles' => $roles]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'no_hp' => ['nullable', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:20'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'angkatan' => ['nullable', 'string', 'max:4'],
        ]);

        $user = $request->user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->nim = $request->nim;
        $user->prodi = $request->prodi;
        $user->angkatan = $request->angkatan;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // img
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $file_name = $user->name . '_' . time() . '.' . $img->getClientOriginalExtension();
            $user->img = $file_name;
            $img->storeAs('public', $file_name);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('message', 'Profile updated successfully');
    }
}
