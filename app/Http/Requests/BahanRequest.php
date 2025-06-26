<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BahanRequest extends FormRequest
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
            'desc' => 'nullable|max:1000',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'unit' => 'required|max:100',
            'stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'date_received' => 'required|date',
            'date_expired' => 'required|date',
            'location' => 'required|max:100',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong!',
            'name.max' => 'Nama maksimal 100 karakter!',
            'desc.max' => 'Deskripsi maksimal 1000 karakter!',
            'img.image' => 'File harus berupa gambar!',
            'img.mimes' => 'Format gambar tidak valid!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'unit.required' => 'Satuan tidak boleh kosong!',
            'unit.max' => 'Satuan maksimal 100 karakter!',
            'stock.required' => 'Stok tidak boleh kosong!',
            'stock.numeric' => 'Stok harus berupa angka!',
            'stock.min' => 'Stok minimal 0!',
            'min_stock.required' => 'Stok minimal tidak boleh kosong!',
            'min_stock.numeric' => 'Stok minimal harus berupa angka!',
            'min_stock.min' => 'Stok minimal minimal 0!',
            'date_received.required' => 'Tanggal diterima tidak boleh kosong!',
            'date_received.date' => 'Tanggal diterima harus berupa tanggal!',
            'date_expired.required' => 'Tanggal kadaluarsa tidak boleh kosong!',
            'date_expired.date' => 'Tanggal kadaluarsa harus berupa tanggal!',
            'status.required' => 'Status tidak boleh kosong!',
            'status.max' => 'Status maksimal 100 karakter!',
            'location.required' => 'Lokasi tidak boleh kosong!',
            'location.max' => 'Lokasi maksimal 100 karakter!',
            'category_id.required' => 'Kategori tidak boleh kosong!',
            'category_id.exists' => 'Kategori tidak valid!',
        ];
    }
}
