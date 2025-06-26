<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'dosen_id', 'alat_id', 'bahan_id', 'ruangan_id', 'tujuan_penggunaan', 'catatan', 'tgl_kerusakan', 'tgl_pengembalian', 'waktu_mulai', 'waktu_selesai', 'status_peminjaman', 'status_penggunaan', 'status_pengembalian', 'kondisi_sebelum', 'kondisi_setelah', 'deskripsi_kerusakan', 'gambar_sebelum', 'gambar_setelah', 'surat'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id', 'id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id', 'id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id');
    }

    public function getDurasiPenggunaanAttribute()
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return '-';
        }

        try {
            $start = Carbon::parse($this->waktu_mulai);
            $end = Carbon::parse($this->waktu_selesai);
            $diffInMinutes = $start->diffInMinutes($end);

            $weeks = floor($diffInMinutes / (7 * 24 * 60));
            $remainingMinutes = $diffInMinutes % (7 * 24 * 60);

            $days = floor($remainingMinutes / (24 * 60));
            $remainingMinutes %= (24 * 60);

            $hours = floor($remainingMinutes / 60);
            $minutes = $remainingMinutes % 60;

            $parts = [];

            if ($weeks > 0) $parts[] = "{$weeks} minggu";
            if ($days > 0) $parts[] = "{$days} hari";
            if ($hours > 0) $parts[] = "{$hours} jam";
            if ($minutes > 0) $parts[] = "{$minutes} menit";

            return count($parts) ? implode(' ', $parts) : '0 menit';
        } catch (\Exception $e) {
            return '-';
        }
    }
}
