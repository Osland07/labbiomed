<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruangan_id',
        'user_id',
        'nama',
        'nim_nip',
        'instansi',
        'tujuan',
        'waktu_masuk',
        'waktu_keluar',
        'catatan',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 