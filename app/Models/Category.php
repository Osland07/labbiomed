<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'type'];

    public function alats()
    {
        return $this->hasMany(Alat::class);
    }

    public function bahan()
    {
        return $this->hasMany(Bahan::class);
    }

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class);
    }
}
