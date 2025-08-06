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
            'bahan_id' => 'nullable|exists:bahans,id',
            'ruangan_id' => 'nullable|exists:ruangans,id',
            'tujuan_penggunaan' => 'nullable|max:100',
            'catatan' => 'nullable|max:100',
            'tgl_kerusakan' => 'nullable|max:100',
            'tgl_pengembalian' => 'nullable|max:100',
            'tanggal_penggunaan' => 'nullable|date|after_or_equal:today|before_or_equal:' . now()->addWeek()->toDateString(),
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'status_penggunaan' => 'nullable|max:100',
            'status_pengembalian' => 'nullable|max:100',
            'surat' => 'nullable|max:2048|mimes:pdf',
            'alat_id' => 'nullable|array',
            'alat_id.*' => 'exists:alats,id',
            'qty' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => ':attribute wajib diisi.',
            '*.max' => 'Maksimal 100 karakter.',
            'user_id.exists' => 'Anggota tidak valid.',
            'dosen_id.exists' => 'Dosen tidak valid.',
            'bahan_id.exists' => 'Bahan tidak valid.',
            'ruangan_id.exists' => 'Ruangan tidak valid.',
            'surat.mimes' => 'File harus berformat PDF.',
            'tanggal_penggunaan.after_or_equal' => 'Tanggal penggunaan tidak boleh kurang dari hari ini.',
            'tanggal_penggunaan.before_or_equal' => 'Tanggal penggunaan tidak boleh lebih dari 1 minggu ke depan.',
            'waktu_mulai.date_format' => 'Format waktu mulai tidak valid.',
            'waktu_mulai.after_or_equal' => 'Waktu mulai tidak boleh kurang dari 08:00.',
            'waktu_mulai.before_or_equal' => 'Waktu mulai tidak boleh lebih dari 17:00.',
            'waktu_selesai.date_format' => 'Format waktu selesai tidak valid.',
            'waktu_selesai.after_or_equal' => 'Waktu selesai tidak boleh kurang dari 08:00.',
            'waktu_selesai.before_or_equal' => 'Waktu selesai tidak boleh lebih dari 17:00.',
            'waktu_selesai.after' => 'Waktu selesai harus lebih besar dari waktu mulai.',
            'alat_id.*.exists' => 'Alat yang dipilih tidak valid.',
            'qty.min' => 'Jumlah minimal 1.',
        ];
    }
}
