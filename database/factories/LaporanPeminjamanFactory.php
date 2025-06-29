<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Alat;
use App\Models\LaporanPeminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanPeminjamanFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeThisYear();
        $endDate = $this->faker->dateTimeBetween($startDate, '+7 days');

        // Ambil user dan dosen acak
        $anggotaId = User::role('Mahasiswa')->inRandomOrder()->value('id');
        $dosenId = User::role('Dosen')->inRandomOrder()->value('id');

        // Ambil 2-4 alat acak
        $alatIds = Alat::inRandomOrder()->limit(rand(2, 4))->pluck('id')->toArray();

        return [
            'user_id' => $anggotaId,
            'dosen_id' => $dosenId,
            'alat_id' => $alatIds,
            'jenis_peminjaman' => $this->faker->randomElement(['Pribadi', 'Kelompok']),
            'tujuan_peminjaman' => $this->faker->sentence(3),
            'judul_penelitian' => $this->faker->sentence(5),
            'tgl_peminjaman' => $startDate->format('Y-m-d'),
            'tgl_pengembalian' => $endDate->format('Y-m-d'),
            'surat' => 'file.pdf',
            'status_validasi' => $this->faker->randomElement([
                LaporanPeminjaman::STATUS_MENUNGGU_LABORAN,
                LaporanPeminjaman::STATUS_MENUNGGU_KOORDINATOR,
                LaporanPeminjaman::STATUS_DITERIMA,
                LaporanPeminjaman::STATUS_DITOLAK,
                LaporanPeminjaman::STATUS_SELESAI
            ]),
            'status_kegiatan' => $this->faker->randomElement(['Sedang Berjalan', 'Selesai']),
            'catatan' => $this->faker->sentence(5),
        ];
    }
}
