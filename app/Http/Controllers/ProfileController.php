<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $roles = Role::all();
        return view('profile.edit', ['user' => $request->user(), 'roles' => $roles]);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'img' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'no_hp' => ['nullable', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:20'],
            'prodi' => ['nullable', 'string', 'max:255'],
            'angkatan' => ['nullable', 'string', 'max:4'],
        ];
        // Jika ingin ganti password, validasi tambahan
        if ($request->filled('password')) {
            $rules['current_password'] = ['required'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }
        $request->validate($rules);

        $user = $request->user();

        // Jika ingin ganti password, cek current_password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.'])->withInput();
            }
            $user->password = bcrypt($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->nim = $request->nim;
        $user->prodi = $request->prodi;
        $user->angkatan = $request->angkatan;

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

    public function printIdCard(Request $request)
    {
        $user = $request->user();
        return view('profile.id-card', compact('user'));
    }
}
