<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laporan>
 */
class LaporanFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeThisYear();
        $endDate = $this->faker->dateTimeBetween($startDate, '+7 days');

        $types = ['alat', 'bahan', 'ruangan'];
        $selected = $this->faker->randomElement($types);

        $anggotaId = User::role('Mahasiswa')->inRandomOrder()->value('id');

        $dosenId = User::role('Dosen')->inRandomOrder()->value('id');

        return [
            'user_id' => $anggotaId,
            'dosen_id' => $dosenId,
            'alat_id' => $selected === 'alat' ? $this->faker->numberBetween(1, 10) : null,
            // 'bahan_id' => $selected === 'bahan' ? $this->faker->numberBetween(1, 10) : null,
            'ruangan_id' => $selected === 'ruangan' ? $this->faker->numberBetween(1, 10) : null,
            'tujuan_penggunaan' => $this->faker->sentence(3),
            'catatan' => $this->faker->sentence(5),
            'tgl_pengembalian' => $endDate->format('Y-m-d H:i'),
            'tgl_kerusakan' => $endDate->format('Y-m-d'),
            'waktu_mulai' => $startDate->format('Y-m-d H:i'),
            'waktu_selesai' => $endDate->format('Y-m-d H:i'),
            'status_peminjaman' => $this->faker->randomElement(['Diterima', 'Menunggu', 'Ditolak']),
            'status_penggunaan' => $this->faker->randomElement(['Tepat Waktu', 'Terlambat']),
            'status_pengembalian' => $this->faker->randomElement(['Sudah Dikembalikan', 'Belum Dikembalikan']),
            'kondisi_sebelum' => $this->faker->randomElement(['Baik']),
            'kondisi_setelah' => $this->faker->randomElement(['Baik', 'Rusak']),
            'deskripsi_kerusakan' => $this->faker->sentence(10),
        ];
    }
}
