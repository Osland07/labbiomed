<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'laporan_peminjamans';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'dosen_id', 'anggota_id', 'alat_id', 'jenis_peminjaman', 'tujuan_peminjaman', 'judul_penelitian', 'surat', 'tgl_peminjaman', 'tgl_pengembalian', 'status_validasi', 'status_kegiatan', 'catatan'];

    protected $casts = [
        'anggota_id' => 'array',
        'alat_id' => 'array',
    ];

    public function anggotas()
    {
        return $this->belongsToMany(User::class, 'laporan_anggota', 'laporan_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function alatList()
    {
        return Alat::whereIn('id', $this->alat_id)->get();
    }
}
