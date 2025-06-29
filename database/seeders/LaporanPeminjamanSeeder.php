<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LaporanPeminjaman;
use App\Models\User;
use App\Models\Alat;

class LaporanPeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat beberapa data dengan status Menunggu Laboran (agar tombol validasi muncul)
        $this->createPeminjamanWithStatus(LaporanPeminjaman::STATUS_MENUNGGU_LABORAN, 5);
        
        // Buat beberapa data dengan status Menunggu Koordinator
        $this->createPeminjamanWithStatus(LaporanPeminjaman::STATUS_MENUNGGU_KOORDINATOR, 3);
        
        // Buat beberapa data dengan status lainnya
        $this->createPeminjamanWithStatus(LaporanPeminjaman::STATUS_DITERIMA, 3);
        $this->createPeminjamanWithStatus(LaporanPeminjaman::STATUS_DITOLAK, 2);
        $this->createPeminjamanWithStatus(LaporanPeminjaman::STATUS_SELESAI, 2);
    }

    private function createPeminjamanWithStatus($status, $count)
    {
        for ($i = 0; $i < $count; $i++) {
            $startDate = now()->addDays(rand(1, 30));
            $endDate = $startDate->copy()->addDays(rand(1, 7));
            
            $anggotaId = User::role('Mahasiswa')->inRandomOrder()->value('id');
            $dosenId = User::role('Dosen')->inRandomOrder()->value('id');
            $alatIds = Alat::inRandomOrder()->limit(rand(2, 4))->pluck('id')->toArray();

            LaporanPeminjaman::create([
                'user_id' => $anggotaId,
                'dosen_id' => $dosenId,
                'alat_id' => $alatIds,
                'jenis_peminjaman' => fake()->randomElement(['Pribadi', 'Kelompok']),
                'tujuan_peminjaman' => fake()->sentence(3),
                'judul_penelitian' => fake()->sentence(5),
                'tgl_peminjaman' => $startDate->format('Y-m-d'),
                'tgl_pengembalian' => $endDate->format('Y-m-d'),
                'surat' => null,
                'status_validasi' => $status,
                'status_kegiatan' => fake()->randomElement(['Sedang Berjalan', 'Selesai']),
                'catatan' => fake()->sentence(5),
            ]);
        }
    }
}
