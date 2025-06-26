<?php

namespace Database\Seeders;

use App\Models\AutoValidate;
use Illuminate\Database\Seeder;

class AutoValidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AutoValidate::create([
            'peminjaman' => false,
            'penggunaan' => false,
            'pengembalian' => false,
        ]);
    }
}
