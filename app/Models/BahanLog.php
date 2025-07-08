<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanLog extends Model
{
    use HasFactory;
    protected $table = 'bahan_logs';
    protected $fillable = [
        'bahan_id', 'user_id', 'tipe', 'jumlah', 'keterangan'
    ];
    
    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 