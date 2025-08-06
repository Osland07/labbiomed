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
use Illuminate\Support\Facades\DB;

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

        // Query untuk mengelompokkan data berdasarkan user, waktu, dan tujuan
        $query = Laporan::select([
            'user_id',
            'waktu_mulai',
            'waktu_selesai', 
            'tujuan_penggunaan',
            'status_peminjaman',
            'catatan',
            \DB::raw('GROUP_CONCAT(DISTINCT CASE 
                WHEN alat_id IS NOT NULL THEN CONCAT("Alat:", alat_id)
                WHEN bahan_id IS NOT NULL THEN CONCAT("Bahan:", bahan_id) 
                WHEN ruangan_id IS NOT NULL THEN CONCAT("Ruangan:", ruangan_id)
                END SEPARATOR ",") as items'),
            \DB::raw('COUNT(*) as total_items'),
            \DB::raw('MIN(id) as first_id')
        ])
        ->where('status_peminjaman', 'Menunggu')
        ->groupBy('user_id', 'waktu_mulai', 'waktu_selesai', 'tujuan_penggunaan', 'status_peminjaman', 'catatan');

        $query->orderByRaw("CASE WHEN status_peminjaman = 'Menunggu' THEN 0 ELSE 1 END");

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $laporans = $query->paginate($validPerPage);

        return view("admin.transaksi.penggunaan.index", compact('laporans', 'search', 'perPage', 'autoValidate'));
    }

    public function validasiPenggunaan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
            'tujuan_penggunaan' => 'required|string',
            'status_peminjaman' => 'required|in:Diterima,Ditolak',
            'catatan' => 'nullable|string|max:1000',
            'gambar_sebelum' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil semua laporan yang sesuai dengan kriteria
        $laporans = Laporan::where('user_id', $request->user_id)
            ->where('waktu_mulai', $request->waktu_mulai)
            ->where('waktu_selesai', $request->waktu_selesai)
            ->where('tujuan_penggunaan', $request->tujuan_penggunaan)
            ->where('status_peminjaman', 'Menunggu')
            ->get();

        if ($laporans->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if ($request->status_peminjaman === 'Ditolak') {
            $request->validate([
                'catatan' => 'required|string|max:1000',
            ], [
                'catatan.required' => 'Catatan harus diisi.',
            ]);
        }

        // Proses setiap laporan
        foreach ($laporans as $laporan) {
            if ($request->status_peminjaman === 'Diterima' && $request->hasFile('gambar_sebelum')) {
                $imagePath = $request->file('gambar_sebelum')->store('gambar_sebelum', 'public');
                $laporan->gambar_sebelum = $imagePath;
            }

            if ($request->status_peminjaman === 'Ditolak') {
                // Kembalikan status alat/ruangan jika ditolak
                if ($laporan->alat !== null) {
                    $laporan->alat->status = 'Tersedia';
                    $laporan->alat->save();
                }

                if ($laporan->ruangan !== null) {
                    $laporan->ruangan->status = 'Tersedia';
                    $laporan->ruangan->save();
                }
            }

            $laporan->status_peminjaman = $request->status_peminjaman;
            $laporan->status_pengembalian = 'Belum Dikembalikan';
            $laporan->catatan = $request->catatan;
            $laporan->updated_at = now();
            $laporan->save();

            // Send email notification untuk setiap laporan
            $laporan->user->notify(new PenggunaanValidatedNotification($laporan, $request->status_peminjaman, $request->catatan));
        }

        $itemCount = $laporans->count();
        $statusText = $request->status_peminjaman === 'Diterima' ? 'disetujui' : 'ditolak';
        
        return redirect()->back()->with('message', "Validasi penggunaan {$itemCount} item berhasil {$statusText}.");
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

        // Tampilkan alat yang belum dikembalikan dan yang sudah dikembalikan user (butuh validasi)
        $query = Laporan::where('status_peminjaman', 'Diterima')
            ->where('status_pengembalian', 'Belum Dikembalikan');

        // Urutkan: yang butuh validasi (sudah dikembalikan user) di atas
        $query->orderByRaw("CASE WHEN tgl_pengembalian IS NOT NULL THEN 0 ELSE 1 END")
              ->orderBy('updated_at', 'desc');

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
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
            if ($request->kondisi_setelah === 'Rusak') {
                $laporan->alat->status = 'Rusak';
                $laporan->alat->condition = 'Rusak';
            } else {
                $laporan->alat->status = 'Tersedia';
                $laporan->alat->condition = 'Baik';
            }
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
