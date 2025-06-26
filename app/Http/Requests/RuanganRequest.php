<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:100',
            ],
            'auto_validate' => 'nullable|boolean',
            'kapasitas' => 'required|numeric',
            'gedung' => 'required|max:100',
            'lantai' => 'required|max:100',
            'keterangan' => 'nullable|max:1000',
            'foto_ruangan' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'foto_denah' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Nama maksimal 100 karakter!',
            'kapasitas.required' => 'Kapasitas tidak boleh kosong!',
            'kapasitas.numeric' => 'Kapasitas harus berupa angka!',
            'gedung.required' => 'Gedung tidak boleh kosong!',
            'gedung.max' => 'Gedung maksimal 100 karakter!',
            'lantai.required' => 'Lantai tidak boleh kosong!',
            'lantai.max' => 'Lantai maksimal 100 karakter!',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter!',
            'foto_ruangan.image' => 'File harus berupa gambar!',
            'foto_ruangan.mimes' => 'Format gambar tidak valid!',
            'foto_ruangan.max' => 'Ukuran gambar maksimal 2MB!',
            'foto_denah.image' => 'File harus berupa gambar!',
            'foto_denah.mimes' => 'Format gambar tidak valid!',
            'foto_denah.max' => 'Ukuran gambar maksimal 2MB!',
        ];
    }
}
