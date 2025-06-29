<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'laporan_peminjamans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 
        'dosen_id', 
        'anggota_id', 
        'alat_id', 
        'jenis_peminjaman', 
        'tujuan_peminjaman', 
        'judul_penelitian', 
        'surat', 
        'tgl_peminjaman', 
        'tgl_pengembalian', 
        'status_validasi', 
        'status_kegiatan', 
        'catatan',
        'validated_by_laboran',
        'validated_by_koordinator',
        'validated_at_laboran',
        'validated_at_koordinator',
        'catatan_laboran',
        'catatan_koordinator'
    ];

    protected $casts = [
        'anggota_id' => 'array',
        'alat_id' => 'array',
        'validated_at_laboran' => 'datetime',
        'validated_at_koordinator' => 'datetime',
    ];

    // Status constants
    const STATUS_MENUNGGU_LABORAN = 'Menunggu Laboran';
    const STATUS_MENUNGGU_KOORDINATOR = 'Menunggu Koordinator';
    const STATUS_DITERIMA = 'Diterima';
    const STATUS_DITOLAK = 'Ditolak';
    const STATUS_SELESAI = 'Selesai';

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

    // Relationship untuk validasi
    public function laboran()
    {
        return $this->belongsTo(User::class, 'validated_by_laboran');
    }

    public function koordinator()
    {
        return $this->belongsTo(User::class, 'validated_by_koordinator');
    }

    // Helper methods
    public function isMenungguLaboran()
    {
        return $this->status_validasi === self::STATUS_MENUNGGU_LABORAN;
    }

    public function isMenungguKoordinator()
    {
        return $this->status_validasi === self::STATUS_MENUNGGU_KOORDINATOR;
    }

    public function isDiterima()
    {
        return $this->status_validasi === self::STATUS_DITERIMA;
    }

    public function isDitolak()
    {
        return $this->status_validasi === self::STATUS_DITOLAK;
    }

    public function isSelesai()
    {
        return $this->status_validasi === self::STATUS_SELESAI;
    }

    public function canBeValidatedByLaboran()
    {
        return $this->isMenungguLaboran();
    }

    public function canBeValidatedByKoordinator()
    {
        return $this->isMenungguKoordinator();
    }

    public function canGenerateSurat()
    {
        return $this->isDiterima();
    }

    public function canUploadSurat()
    {
        return $this->isDiterima() && !$this->surat;
    }
}
