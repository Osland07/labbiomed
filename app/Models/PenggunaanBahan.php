<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanBahan extends Model
{
    use HasFactory;

    protected $table = 'penggunaan_bahan';
    protected $fillable = [
        'user_id', 'bahan_id', 'jumlah', 'tujuan', 'status', 'keterangan'
    ];
    
    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }
} 