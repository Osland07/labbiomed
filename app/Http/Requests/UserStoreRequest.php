<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');

        return [
            'name' => 'required|max:100',
            'email' => [
                'required',
                'max:255',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'no_hp' => 'nullable|max:255',
            'nim' => 'nullable|max:20',
            'prodi' => 'nullable|max:100',
            'angkatan' => 'nullable|max:255',
            'password' => 'required|min:8',
            'role' => 'required',
            'status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Nama maksimal 100 karakter!',
            'email.required' => 'Email tidak boleh kosong!',
            'email.max' => 'Email maksimal 255 karakter!',
            'email.unique' => 'Email sudah terdaftar!',
            'no_hp.max' => 'No. HP maksimal 255 karakter!',
            'nim.max' => 'NIM maksimal 9 karakter!',
            'prodi.max' => 'Prodi maksimal 100 karakter!',
            'angkatan.max' => 'Angkatan maksimal 255 karakter!',
            'password.required' => 'Kata sandi tidak boleh kosong!',
            'password.min' => 'Kata sandi minimal 8 karakter!',
            'role.required' => 'Role tidak boleh kosong!',
            'status.required' => 'Status tidak boleh kosong!',
        ];
    }
}
