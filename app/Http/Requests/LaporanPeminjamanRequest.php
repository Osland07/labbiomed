<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanPeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'nullable|exists:users,id',
            'dosen_id' => 'nullable|exists:users,id',
            'alat_id' => 'nullable|exists:alats,id',
            'jenis_peminjaman' => 'nullable|max:100',
            'tujuan_peminjaman' => 'nullable|max:100',
            'surat' => 'nullable|max:4096|mimes:pdf',
            'tgl_peminjaman' => 'required|max:100',
            'tgl_pengembalian' => 'required|max:100',
            'status_validasi' => 'nullable|max:100',
            'status_kegiatan' => 'nullable|max:100',
            'catatan' => 'nullable|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ':attribute wajib diisi.',
            '*.max' => 'Maksimal 100 karakter.',
            'user_id.exists' => 'Anggota tidak valid.',
            'dosen_id.exists' => 'Dosen tidak valid.',
            'alat_id.exists' => 'Alat tidak valid.',
        ];
    }
}
