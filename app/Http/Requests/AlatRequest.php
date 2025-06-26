<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlatRequest extends FormRequest
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
            'qty' => 'nullable|integer|min:1|max:1000000',
            'desc' => 'nullable|max:1000',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'condition' => 'nullable|max:100',
            'status' => 'nullable|max:100',
            'location' => 'required|max:100',
            'detail_location' => 'nullable|max:200',
            'date_received' => 'nullable|date',
            'source' => 'nullable|max:100|in:Modal,Hibah,Fakultas',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Nama maksimal 100 karakter!',
            'qty.integer' => 'Jumlah harus berupa angka!',
            'qty.min' => 'Jumlah minimal 1!',
            'qty.max' => 'Jumlah maksimal 1000000!',
            'desc.max' => 'Deskripsi maksimal 1000 karakter!',
            'img.image' => 'File harus berupa gambar!',
            'img.mimes' => 'Format gambar tidak valid!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'condition.required' => 'Kondisi tidak boleh kosong!',
            'condition.max' => 'Kondisi maksimal 100 karakter!',
            'status.required' => 'Status tidak boleh kosong!',
            'status.max' => 'Status maksimal 100 karakter!',
            'location.required' => 'Lokasi tidak boleh kosong!',
            'location.max' => 'Lokasi maksimal 100 karakter!',
            'detail_location.max' => 'Detail lokasi maksimal 200 karakter!',
            'date_received.date' => 'Format tanggal tidak valid!',
            'source.max' => 'Sumber maksimal 100 karakter!',
            'source.in' => 'Sumber tidak valid!',
            'category_id.required' => 'Kategori tidak boleh kosong!',
            'category_id.exists' => 'Kategori tidak valid!',
        ];
    }
}
