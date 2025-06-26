<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahans';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'serial_number', 'desc', 'img', 'unit', 'stock', 'min_stock', 'date_received', 'date_expired', 'location', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
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
        $code = 'B1';
        $year = date('Y', strtotime($this->date_received ?? now()));
        $nextId = static::max('id') + 1;
        $id = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $category = $this->category_id ?? 0;

        return "{$code}-{$year}-{$id}-{$category}";
    }
}
