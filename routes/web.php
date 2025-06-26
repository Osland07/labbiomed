<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminAlatController;
use App\Http\Controllers\Admin\AdminBahanController;
use App\Http\Controllers\Admin\AdminRuanganController;
use App\Http\Controllers\Admin\AdminTransaksiController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Client\ClientCekController;
use App\Http\Controllers\Client\ClientPengajuanController;
use App\Http\Controllers\Client\ClientPenggunaanController;
use App\Http\Controllers\Client\ClientRiwayatController;
use App\Http\Controllers\JadwalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',  [AuthenticatedSessionController::class, 'create'])->name('beranda');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // CMS ADMINITRASTOR
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('beranda');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('role', AdminRoleController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('user', AdminUserController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('alat', AdminAlatController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('bahan', AdminBahanController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('category', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('ruangan', AdminRuanganController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::put('/auto-validate/peminjaman', [AdminTransaksiController::class, 'autoValidatePeminjaman'])->name('auto-validate.peminjaman');
        Route::put('/auto-validate/penggunaan', [AdminTransaksiController::class, 'autoValidatePenggunaan'])->name('auto-validate.penggunaan');
        Route::put('/auto-validate/pengembalian', [AdminTransaksiController::class, 'autoValidatePengembalian'])->name('auto-validate.pengembalian');
        Route::get('/transaksi/peminjaman', [AdminTransaksiController::class, 'transaksiPeminjaman'])->name('transaksi.peminjaman');
        Route::get('/transaksi/penggunaan', [AdminTransaksiController::class, 'transaksiPenggunaan'])->name('transaksi.penggunaan');
        Route::get('/transaksi/pengembalian', [AdminTransaksiController::class, 'transaksiPengembalian'])->name('transaksi.pengembalian');
        Route::post('/transaksi/peminjaman/{id}', [AdminTransaksiController::class, 'validasiPeminjaman'])->name('validasi.peminjaman');
        Route::post('/transaksi/penggunaan', [AdminTransaksiController::class, 'validasiPenggunaan'])->name('validasi.penggunaan');
        Route::post('/transaksi/pengembalian', [AdminTransaksiController::class, 'validasiPengembalian'])->name('validasi.pengembalian');
        Route::get('/laporan/peminjaman', [AdminLaporanController::class, 'laporanPeminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/penggunaan', [AdminLaporanController::class, 'laporanPenggunaan'])->name('laporan.penggunaan');
        Route::get('/laporan/kerusakan', [AdminLaporanController::class, 'laporanKerusakan'])->name('laporan.kerusakan');
        Route::get('/laporan/exportPeminjaman', [AdminLaporanController::class, 'exportLaporanPeminjaman'])->name('laporan.peminjaman.export');
        Route::get('/laporan/exportPenggunaan', [AdminLaporanController::class, 'exportLaporanPenggunaan'])->name('laporan.penggunaan.export');
        Route::get('/laporan/exportKerusakan', [AdminLaporanController::class, 'exportLaporanKerusakan'])->name('laporan.kerusakan.export');
    });

    // CMS CLIENT
    Route::name('client.')->prefix('client')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('beranda');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('check', ClientCekController::class)->only(['index']);
        Route::get('/pengajuan-peminjaman', [ClientPengajuanController::class, 'index'])->name('pengajuan-peminjaman.index');
        Route::get('/upload-surat', [ClientPengajuanController::class, 'upload'])->name('pengajuan-peminjaman.upload');
        Route::post('/upload-surat/{id}', [ClientPengajuanController::class, 'storeUpload'])->name('pengajuan-peminjaman.storeUpload');
        Route::post('/pengajuan-peminjaman', [ClientPengajuanController::class, 'store'])->name('pengajuan-peminjaman.store');
        Route::get('/generate-formulir/{id}', [ClientPengajuanController::class, 'generateFormulir'])->name('pengajuan-peminjaman.generate-formulir');
        Route::get('/penggunaan-alat', [ClientPenggunaanController::class, 'indexAlat'])->name('penggunaan-alat');
        Route::post('/penggunaan-alat', [ClientPenggunaanController::class, 'storeAlat'])->name('penggunaan-alat.store');
        Route::get('/penggunaan-ruangan', [ClientPenggunaanController::class, 'indexRuangan'])->name('penggunaan-ruangan');
        Route::post('/penggunaan-ruangan', [ClientPenggunaanController::class, 'storeRuangan'])->name('penggunaan-ruangan.store');
        Route::get('/riwayat-pengajuan', [ClientRiwayatController::class, 'riwayatPengajuan'])->name('riwayat-pengajuan');
        Route::get('/riwayat-penggunaan', [ClientRiwayatController::class, 'riwayatPenggunaan'])->name('riwayat-penggunaan');
        Route::post('/penggunaan-alat/kembalikan/{id}', [ClientPenggunaanController::class, 'kembalikanAlat'])->name('penggunaan-alat.kembalikan');
    });

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
});

require __DIR__ . '/auth.php';
