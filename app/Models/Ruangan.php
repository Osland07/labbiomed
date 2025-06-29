<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'serial_number', 'auto_validate', 'kapasitas', 'gedung', 'lantai', 'status', 'foto_ruangan', 'foto_denah', 'keterangan', 'category_id'];

    protected $casts = [
        'auto_validate' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function alats()
    {
        return $this->hasMany(Alat::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bahan) {
            $bahan->serial_number = $bahan->generateSerialNumber();
        });
    }

    public function generateSerialNumber()
    {
        $code = 'R1';
        $year = date('Y', strtotime($this->date_received ?? now()));
        $nextId = static::max('id') + 1;
        $id = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $category = $this->category_id ?? 0;

        return "{$code}-{$year}-{$id}-{$category}";
    }
}
