<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanRequest extends FormRequest
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
            'bahan_id' => 'nullable|exists:bahans,id',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'tujuan_penggunaan' => 'nullable|max:100',
            'catatan' => 'nullable|max:100',
            'tgl_kerusakan' => 'nullable|max:100',
            'tgl_pengembalian' => 'nullable|max:100',
            'waktu_mulai' => 'required|max:100',
            'waktu_selesai' => 'required|max:100',
            'status_penggunaan' => 'nullable|max:100',
            'status_pengembalian' => 'nullable|max:100',
            'surat' => 'nullable|max:2048|mimes:pdf',
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
            'bahan_id.exists' => 'Bahan tidak valid.',
            'ruangan_id.exists' => 'Ruangan tidak valid.',
            'surat.mimes' => 'File harus berformat PDF.',
        ];
    }
}
