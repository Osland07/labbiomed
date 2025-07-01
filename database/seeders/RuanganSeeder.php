<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = $this->getOrCreateCategory('Ruangan');

        // Ruangan sesuai permintaan
        $ruangans = [
            [
                'name' => 'Laboratorium Instrumentasi',
                'kapasitas' => 30,
                'gedung' => 'Labtek 5',
                'lantai' => 'Lantai 4',
                'status' => 'Tersedia',
                'keterangan' => 'Laboratorium untuk praktikum dan riset instrumentasi biomedis.'
            ],
            [
                'name' => 'Laboratorium Biokompabilitas',
                'kapasitas' => 25,
                'gedung' => 'Labtek 5',
                'lantai' => 'Lantai 4',
                'status' => 'Tersedia',
                'keterangan' => 'Laboratorium untuk pengujian biokompabilitas material dan alat.'
            ],
            [
                'name' => 'Laboratorium Rekayasa Sel dan Jaringan',
                'kapasitas' => 20,
                'gedung' => 'Labtek 5',
                'lantai' => 'Lantai 4',
                'status' => 'Tersedia',
                'keterangan' => 'Laboratorium untuk penelitian rekayasa sel dan jaringan.'
            ],
            [
                'name' => 'Laboratorium Pencitraan Medis',
                'kapasitas' => 20,
                'gedung' => 'Labtek 5',
                'lantai' => 'Lantai 4',
                'status' => 'Tersedia',
                'keterangan' => 'Laboratorium untuk praktikum dan riset pencitraan medis.'
            ],
        ];
        foreach ($ruangans as $r) {
            Ruangan::create(array_merge($r, ['category_id' => $categoryId]));
        }
    }

    private function getOrCreateCategory(string $name): int
    {
        $category = Category::firstOrCreate(
            ['name' => $name],
            ['type' => 'ruangan']
        );

        return $category->id;
    }
}
