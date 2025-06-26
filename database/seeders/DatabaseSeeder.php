<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AddressProvince;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(AlatSeeder::class);
        $this->call(BahanSeeder::class);
        $this->call(RuanganSeeder::class);
        $this->call(AutoValidateSeeder::class);
        // $this->call(LaporanPeminjamanSeeder::class);
        // $this->call(LaporanSeeder::class);
    }
}
