<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Models\Laporan;
use App\Models\LaporanPeminjaman;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Carbon::setLocale('id');

        View::composer('*', function ($view) {
            $jumlahValidasiPeminjaman = \App\Models\LaporanPeminjaman::where('status_validasi', 'Menunggu')->count();
            $jumlahValidasiPenggunaan = \App\Models\Laporan::where('status_peminjaman', 'Menunggu')->count();
            $jumlahValidasiPengembalian = \App\Models\Laporan::where('status_peminjaman', 'Diterima')->where('status_pengembalian', 'Belum Dikembalikan')->count();

            $view->with([
                'jumlahValidasiPeminjaman' => $jumlahValidasiPeminjaman,
                'jumlahValidasiPenggunaan' => $jumlahValidasiPenggunaan,
                'jumlahValidasiPengembalian' => $jumlahValidasiPengembalian,
            ]);
        });
    }
}
