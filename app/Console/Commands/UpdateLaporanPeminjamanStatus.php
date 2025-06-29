<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LaporanPeminjaman;

class UpdateLaporanPeminjamanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laporan:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status validasi yang kosong pada laporan peminjaman';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai update status validasi laporan peminjaman...');

        // Update data dengan status_validasi null atau kosong
        $updatedCount = LaporanPeminjaman::whereNull('status_validasi')
            ->orWhere('status_validasi', '')
            ->update(['status_validasi' => LaporanPeminjaman::STATUS_MENUNGGU_LABORAN]);

        $this->info("Berhasil mengupdate {$updatedCount} record dengan status 'Menunggu Laboran'");

        // Tampilkan statistik
        $this->info('Statistik status validasi:');
        $statuses = LaporanPeminjaman::select('status_validasi')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status_validasi')
            ->get();

        foreach ($statuses as $status) {
            $statusName = $status->status_validasi ?: 'NULL/Kosong';
            $this->line("- {$statusName}: {$status->count}");
        }

        // Tampilkan statistik surat
        $this->info('Statistik upload surat:');
        $suratStats = LaporanPeminjaman::selectRaw('CASE WHEN surat IS NOT NULL THEN "Sudah Upload" ELSE "Belum Upload" END as status_surat')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status_surat')
            ->get();

        foreach ($suratStats as $stat) {
            $this->line("- {$stat->status_surat}: {$stat->count}");
        }

        $this->info('Update selesai!');
    }
} 