<?php

namespace Database\Factories;

use App\Models\Bahan;
use Illuminate\Database\Eloquent\Factories\Factory;

class BahanFactory extends Factory
{
    protected $model = Bahan::class;

    public function definition(): array
    {
        return [
            'name' => 'Bahan ' . $this->faker->unique()->numerify('##'),
            'desc' => $this->faker->sentence(6),
            'unit' => $this->faker->randomElement(['Kg', 'Liter', 'Pcs', 'Unit']),
            'stock' => $this->faker->numberBetween(10, 100),
            'min_stock' => $this->faker->numberBetween(1, 90),
            'date_received' => now()->format('Y-m-d'),
            'date_expired' => now()->addMonth()->format('Y-m-d'),
            'location' => 'Room ' . $this->faker->randomElement(['B101', 'B102', 'C201', 'D301']),
            'category_id' => $this->faker->numberBetween(6, 10),
        ];
    }
}
