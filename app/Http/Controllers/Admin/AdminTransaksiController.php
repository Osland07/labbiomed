<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Laporan;
use App\Models\LaporanPeminjaman;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AutoValidate;
use App\Notifications\PeminjamanValidatedNotification;
use App\Notifications\PenggunaanValidatedNotification;
use App\Notifications\PengembalianValidatedNotification;
use App\Notifications\PeminjamanDitolakNotification;
use App\Notifications\PeminjamanDisetujuiNotification;
use Carbon\Carbon;

class AdminTransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-transaksi')->only(['transaksiPeminjaman', 'transaksiPenggunaan', 'transaksiPengembalian', 'validasiLaboran', 'validasiKoordinator']);
        $this->middleware('permission:penggunaan-transaksi')->only(['validasiPenggunaan']);
        $this->middleware('permission:pengembalian-transaksi')->only(['validasiPengembalian']);
    }

    public function autoValidatePeminjaman(Request $request)
    {
        $autoValidate = AutoValidate::first();
        if ($autoValidate) {
            $autoValidate->peminjaman = $request->peminjaman;
            $autoValidate->save();
        } else {
            AutoValidate::create([
                'peminjaman' => $request->peminjaman,
                'penggunaan' => false,
                'pengembalian' => false,
            ]);
        }

        return redirect()->back()->with('message', 'Auto validate peminjaman berhasil diupdate.');
    }

    public function autoValidatePenggunaan(Request $request)
    {
        $autoValidate = AutoValidate::first();
        if ($autoValidate) {
            $autoValidate->penggunaan = $request->penggunaan;
            $autoValidate->save();
        } else {
            AutoValidate::create([
                'peminjaman' => false,
                'penggunaan' => $request->penggunaan,
                'pengembalian' => false,
            ]);
        }

        return redirect()->back()->with('message', 'Auto validate penggunaan berhasil diupdate.');
    }

    public function autoValidatePengembalian(Request $request)
    {
        $autoValidate = AutoValidate::first();
        if ($autoValidate) {
            $autoValidate->pengembalian = $request->pengembalian;
            $autoValidate->save();
        } else {
            AutoValidate::create([
                'peminjaman' => false,
                'penggunaan' => false,
                'pengembalian' => $request->pengembalian,
            ]);
        }

        return redirect()->back()->with('message', 'Auto validate pengembalian berhasil diupdate.');
    }

    public function transaksiPeminjaman(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $query = LaporanPeminjaman::with(['user', 'dosen', 'laboran', 'koordinator'])
            ->whereIn('status_validasi', [
                LaporanPeminjaman::STATUS_MENUNGGU_LABORAN,
                LaporanPeminjaman::STATUS_MENUNGGU_KOORDINATOR,
                LaporanPeminjaman::STATUS_DITERIMA
            ])
            ->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('judul_penelitian', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

        return view("admin.transaksi.peminjaman.index", compact('laporans', 'search', 'perPage'));
    }

    public function validasiLaboran(Request $request, $id)
    {
        if (!auth()->user()->hasRole('Laboran') && !auth()->user()->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'Anda tidak berhak melakukan validasi ini.');
        }
        $request->validate([
            'status_validasi' => 'required|in:Diterima,Ditolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $laporan = LaporanPeminjaman::findOrFail($id);

        // Cek apakah bisa divalidasi oleh laboran
        if (!$laporan->canBeValidatedByLaboran()) {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dapat divalidasi oleh laboran.');
        }

        if ($request->status_validasi == 'Ditolak') {
            $request->validate([
                'catatan' => 'required|string|max:1000',
            ], [
                'catatan.required' => 'Catatan harus diisi saat menolak pengajuan.',
            ]);

            $laporan->status_validasi = LaporanPeminjaman::STATUS_DITOLAK;
            $laporan->validated_by_laboran = auth()->id();
            $laporan->validated_at_laboran = now();
            $laporan->catatan_laboran = $request->catatan;
            $laporan->save();

            // Email notifikasi penolakan
            $laporan->user->notify(new PeminjamanDitolakNotification($laporan, 'laboran', $request->catatan));

            return redirect()->back()->with('message', 'Pengajuan ditolak oleh laboran.');
        }

        // Jika diterima laboran
        $laporan->status_validasi = LaporanPeminjaman::STATUS_MENUNGGU_KOORDINATOR;
        $laporan->validated_by_laboran = auth()->id();
        $laporan->validated_at_laboran = now();
        $laporan->catatan_laboran = $request->catatan;
        $laporan->save();

        return redirect()->back()->with('message', 'Pengajuan diterima laboran, menunggu validasi koordinator.');
    }

    public function validasiKoordinator(Request $request, $id)
    {
        if (!auth()->user()->hasRole('Koordinator Laboratorium') && !auth()->user()->hasRole('Super Admin')) {
            return redirect()->back()->with('error', 'Anda tidak berhak melakukan validasi ini.');
        }
        $request->validate([
            'status_validasi' => 'required|in:Diterima,Ditolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $laporan = LaporanPeminjaman::findOrFail($id);

        // Cek apakah bisa divalidasi oleh koordinator
        if (!$laporan->canBeValidatedByKoordinator()) {
            return redirect()->back()->with('error', 'Pengajuan ini tidak dapat divalidasi oleh koordinator.');
        }

        if ($request->status_validasi == 'Ditolak') {
            $request->validate([
                'catatan' => 'required|string|max:1000',
            ], [
                'catatan.required' => 'Catatan harus diisi saat menolak pengajuan.',
            ]);

            $laporan->status_validasi = LaporanPeminjaman::STATUS_DITOLAK;
            $laporan->validated_by_koordinator = auth()->id();
            $laporan->validated_at_koordinator = now();
            $laporan->catatan_koordinator = $request->catatan;
            $laporan->save();

            // Email notifikasi penolakan
            $laporan->user->notify(new PeminjamanDitolakNotification($laporan, 'koordinator', $request->catatan));

            return redirect()->back()->with('message', 'Pengajuan ditolak oleh koordinator.');
        }

        // Jika diterima koordinator
        $laporan->status_validasi = LaporanPeminjaman::STATUS_DITERIMA;
        $laporan->status_kegiatan = 'Sedang Berjalan';
        $laporan->validated_by_koordinator = auth()->id();
        $laporan->validated_at_koordinator = now();
        $laporan->catatan_koordinator = $request->catatan;
        $laporan->save();

        // Email notifikasi penerimaan + link download
        $laporan->user->notify(new PeminjamanDisetujuiNotification($laporan));

        return redirect()->back()->with('message', 'Pengajuan disetujui koordinator. Surat telah digenerate.');
    }

    public function transaksiPenggunaan(Request $request)
    {
        $autoValidate = AutoValidate::first();

        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        // AUTO VALIDATE PENGGUNAAN
        if ($autoValidate && $autoValidate->penggunaan) {
            $toValidate = Laporan::where('status_peminjaman', 'Menunggu')->get();
            foreach ($toValidate as $laporan) {
                $laporan->status_peminjaman = 'Diterima';
                $laporan->status_pengembalian = 'Belum Dikembalikan';
                $laporan->updated_at = now();
                $laporan->save();
            }
        }

        $query = Laporan::where('status_peminjaman', 'Menunggu');

        $query->orderByRaw("CASE WHEN status_peminjaman = 'Menunggu' THEN 0 ELSE 1 END");

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

        return view("admin.transaksi.penggunaan.index", compact('laporans', 'search', 'perPage', 'autoValidate'));
    }

    public function validasiPenggunaan(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporans,id',
            'status_peminjaman' => 'required|in:Diterima,Ditolak',
            'catatan' => 'nullable|string|max:1000',
            'gambar_sebelum' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $laporan = Laporan::findOrFail($request->laporan_id);

        if ($request->status_peminjaman === 'Diterima' && $request->hasFile('gambar_sebelum')) {
            $imagePath = $request->file('gambar_sebelum')->store('gambar_sebelum', 'public');
            $laporan->gambar_sebelum = $imagePath;
        }

        if ($request->status_peminjaman === 'Ditolak') {
            $request->validate([
                'catatan' => 'required|string|max:1000',
            ], [
                'catatan.required' => 'Catatan harus diisi.',
            ]);
        }

        if ($request->status_peminjaman === 'Ditolak' && $laporan->alat !== null) {
            $laporan->alat->status = 'Tersedia';
            $laporan->alat->save();
        }

        if ($request->status_peminjaman === 'Ditolak' && $laporan->ruangan !== null) {
            $laporan->ruangan->status = 'Tersedia';
            $laporan->ruangan->save();
        }

        $laporan->status_peminjaman = $request->status_peminjaman;
        $laporan->status_pengembalian = 'Belum Dikembalikan';
        $laporan->catatan = $request->catatan;
        $laporan->updated_at = now();
        $laporan->save();

        // Send email notification
        $laporan->user->notify(new PenggunaanValidatedNotification($laporan, $request->status_peminjaman, $request->catatan));

        return redirect()->back()->with('message', 'Validasi penggunaan berhasil dilakukan.');
    }

    public function transaksiPengembalian(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $query = Laporan::where('status_peminjaman', 'Diterima')->where('status_pengembalian', 'Belum Dikembalikan');

        $query->orderByRaw("CASE WHEN status_pengembalian = 'Belum Dikembalikan' THEN 0 ELSE 1 END");

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

        return view("admin.transaksi.pengembalian.index", compact('laporans', 'search', 'perPage'));
    }

    public function validasiPengembalian(Request $request)
    {
        $request->validate([
            'laporan_id' => 'required|exists:laporans,id',
            'kondisi_setelah' => 'required|in:Baik,Rusak',
            'deskripsi_kerusakan' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
            'gambar_setelah' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $laporan = Laporan::findOrFail($request->laporan_id);

        if ($request->hasFile('gambar_setelah')) {
            $path = $request->file('gambar_setelah')->store('gambar_setelah', 'public');
            $laporan->gambar_setelah = $path;
        }

        if ($request->kondisi_setelah === 'Rusak') {
            $request->validate([
                'deskripsi_kerusakan' => 'required|string|max:1000',
            ], [
                'deskripsi_kerusakan.required' => 'Deskripsi kerusakan harus diisi.',
            ]);
        }

        $laporan->status_pengembalian = 'Sudah Dikembalikan';
        $laporan->tgl_pengembalian = now();
        $laporan->kondisi_setelah = $request->kondisi_setelah;
        $laporan->catatan = $request->catatan;
        $laporan->updated_at = now();

        if ($laporan->alat !== null) {
            $laporan->alat->status = 'Tersedia';
            $laporan->alat->save();
        }

        if ($laporan->ruangan !== null) {
            $laporan->ruangan->status = 'Tersedia';
            $laporan->ruangan->save();
        }

        $laporan->save();

        // Send email notification
        $laporan->user->notify(new PengembalianValidatedNotification($laporan, $request->kondisi_setelah, $request->catatan));

        return redirect()->back()->with('message', 'Validasi pengembalian berhasil dilakukan.');
    }
}
