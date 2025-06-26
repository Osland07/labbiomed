<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoValidate extends Model
{
    use HasFactory;

    protected $table = 'auto_validates';
    protected $fillable = [
        'peminjaman',
        'penggunaan',
        'pengembalian',
    ];

    protected $casts = [
        'peminjaman' => 'boolean',
        'penggunaan' => 'boolean',
        'pengembalian' => 'boolean',
    ];
}
