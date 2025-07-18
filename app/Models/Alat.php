<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alats';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'serial_number', 'auto_validate', 'desc', 'img', 'condition', 'status', 'location', 'detail_location', 'date_received', 'source', 'category_id'];

    protected $casts = [
        'auto_validate' => 'boolean',
    ];

    public const STATUS_MAINTENANCE = 'Maintenance';
    public const STATUS_RUSAK = 'Rusak';
    public const STATUS_TERSEDIA = 'Tersedia';
    public const STATUS_DIPINJAM = 'Dipinjam';

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'location', 'id');
    }
}
