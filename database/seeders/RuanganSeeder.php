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

        // Ruangan 1–5 (GKU 1, Lantai 1, Kapasitas 50)
        for ($i = 1; $i <= 5; $i++) {
            Ruangan::create([
                'name' => "Ruangan $i",
                'kapasitas' => 50,
                'gedung' => 'GKU 1',
                'lantai' => 'Lantai 1',
                'status' => 'Tersedia',
                'keterangan' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, quia.',
                'category_id' => $categoryId,
            ]);
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
