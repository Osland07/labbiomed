<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alat;
use App\Models\Bahan;
use App\Models\Ruangan;
use App\Models\LaporanPeminjaman;
use App\Models\Laporan;
use App\Models\Kunjungan;
use Carbon\Carbon;

class TransaksiDummySeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::now()->subDays(3);
        $end = Carbon::now()->addDays(3);
        $dates = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekday()) {
                $dates[] = $date->copy();
            }
        }

        $mahasiswas = User::role('Mahasiswa')->get();
        $dosens = User::role('Dosen')->get();
        $alats = Alat::all();
        $bahans = Bahan::all();
        $ruangans = Ruangan::all();

        // Hitung jumlah data per hari per fitur
        $dataPerHari = 2; // jumlah data per hari per fitur

        foreach ($dates as $date) {
            // --- Peminjaman ---
            for ($i = 0; $i < $dataPerHari; $i++) {
                $user = $mahasiswas->random();
                $dosen = $dosens->random();
                $alatIds = $alats->random(rand(1, min(3, $alats->count())))->pluck('id')->toArray();
                $tgl_peminjaman = $date->copy();
                $tgl_pengembalian = $tgl_peminjaman->copy()->addDays(rand(1, 7));
                LaporanPeminjaman::create([
                    'user_id' => $user->id,
                    'dosen_id' => $dosen->id,
                    'alat_id' => $alatIds,
                    'jenis_peminjaman' => fake()->randomElement(['Pribadi', 'Kelompok']),
                    'tujuan_peminjaman' => fake()->sentence(3),
                    'judul_penelitian' => fake()->sentence(5),
                    'tgl_peminjaman' => $tgl_peminjaman->format('Y-m-d'),
                    'tgl_pengembalian' => $tgl_pengembalian->format('Y-m-d'),
                    'surat' => null,
                    'status_validasi' => fake()->randomElement([
                        LaporanPeminjaman::STATUS_MENUNGGU_LABORAN,
                        LaporanPeminjaman::STATUS_MENUNGGU_KOORDINATOR,
                        LaporanPeminjaman::STATUS_DITERIMA,
                        LaporanPeminjaman::STATUS_DITOLAK,
                        LaporanPeminjaman::STATUS_SELESAI
                    ]),
                    'status_kegiatan' => fake()->randomElement(['Sedang Berjalan', 'Selesai']),
                    'catatan' => fake()->sentence(5),
                ]);
            }

            // --- Penggunaan ---
            for ($i = 0; $i < $dataPerHari; $i++) {
                $user = $mahasiswas->random();
                $dosen = $dosens->random();
                $alat = $alats->random();
                $ruangan = $ruangans->random();
                $startHour = rand(8, 15); // mulai antara 08:00 - 15:00
                $startMinute = rand(0, 59);
                $mulai = $date->copy()->setTime($startHour, $startMinute);
                $maxDuration = min(17 - $startHour, 4); // maksimal tidak lewat jam 17:00
                $duration = rand(1, max(1, $maxDuration));
                $selesai = $mulai->copy()->addHours($duration);
                if ($selesai->hour > 17 || ($selesai->hour == 17 && $selesai->minute > 0)) {
                    $selesai->setTime(17, 0);
                }
                Laporan::create([
                    'user_id' => $user->id,
                    'dosen_id' => $dosen->id,
                    'alat_id' => $alat->id,
                    'ruangan_id' => $ruangan->id,
                    'tujuan_penggunaan' => fake()->sentence(3),
                    'catatan' => fake()->sentence(5),
                    'waktu_mulai' => $mulai->format('Y-m-d H:i:s'),
                    'waktu_selesai' => $selesai->format('Y-m-d H:i:s'),
                    'status_peminjaman' => fake()->randomElement(['Diterima', 'Menunggu', 'Ditolak']),
                    'status_penggunaan' => fake()->randomElement(['Tepat Waktu', 'Terlambat']),
                    'status_pengembalian' => fake()->randomElement(['Sudah Dikembalikan', 'Belum Dikembalikan']),
                    'kondisi_sebelum' => 'Baik',
                    'kondisi_setelah' => fake()->randomElement(['Baik', 'Rusak']),
                    'deskripsi_kerusakan' => fake()->sentence(10),
                ]);
            }

            // --- Kunjungan ---
            for ($i = 0; $i < $dataPerHari; $i++) {
                $user = $mahasiswas->random();
                $ruangan = $ruangans->random();
                $startHour = rand(8, 15); // mulai antara 08:00 - 15:00
                $startMinute = rand(0, 59);
                $masuk = $date->copy()->setTime($startHour, $startMinute);
                $maxDuration = min(17 - $startHour, 4); // maksimal tidak lewat jam 17:00
                $duration = rand(1, max(1, $maxDuration));
                $keluar = $masuk->copy()->addHours($duration);
                if ($keluar->hour > 17 || ($keluar->hour == 17 && $keluar->minute > 0)) {
                    $keluar->setTime(17, 0);
                }
                Kunjungan::create([
                    'ruangan_id' => $ruangan->id,
                    'user_id' => $user->id,
                    'nama' => $user->name,
                    'nim_nip' => $user->nim,
                    'instansi' => 'ITERA',
                    'tujuan' => fake()->sentence(3),
                    'waktu_masuk' => $masuk->format('Y-m-d H:i:s'),
                    'waktu_keluar' => $keluar->format('Y-m-d H:i:s'),
                    'catatan' => fake()->sentence(5),
                ]);
            }
        }
    }
} 