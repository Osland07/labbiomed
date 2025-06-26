<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'no_hp' => '081234567890',
            'nim' => $this->faker->unique()->numerify('#########'),
            'prodi' => $this->faker->randomElement(['Teknik Biomedis', 'Fisika', 'Kimia', 'Biologi']),
            'angkatan' => $this->faker->year(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
        ];
    }

    // public function configure(): static
    // {
    //     return $this->afterCreating(function (User $user) {
    //         $role = ['Admin', 'Dosen', 'Mahasiswa'];
    //         $user->assignRole($this->faker->randomElement($role));
    //     });
    // }

}
