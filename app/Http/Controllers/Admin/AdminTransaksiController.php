<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Laporan;
use App\Models\LaporanPeminjaman;
use App\Models\AutoValidate;
use Illuminate\Http\Request;

class AdminTransaksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:peminjaman-transaksi')->only(['transaksiPeminjaman', 'validasiPeminjaman']);
        $this->middleware('permission:penggunaan-transaksi')->only(['transaksiPenggunaan', 'validasiPenggunaan']);
        $this->middleware('permission:pengembalian-transaksi')->only(['transaksiPengembalian', 'validasiPengembalian']);
    }

    public function autoValidatePeminjaman(Request $request)
    {
        $request->validate([
            'autoValidate' => 'required|in:1,0',
        ]);

        $autoValidate = AutoValidate::first();
        $autoValidate->peminjaman = $request->autoValidate == '1';
        $autoValidate->save();
        return redirect()->back()->with('message', 'Auto validate peminjaman berhasil diubah.');
    }

    public function autoValidatePenggunaan(Request $request)
    {
        $request->validate([
            'autoValidate' => 'required|in:1,0',
        ]);

        $autoValidate = AutoValidate::first();
        $autoValidate->penggunaan = $request->autoValidate == '1';
        $autoValidate->save();
        return redirect()->back()->with('message', 'Auto validate penggunaan berhasil diubah.');
    }

    public function autoValidatePengembalian(Request $request)
    {
        $request->validate([
            'autoValidate' => 'required|in:1,0',
        ]);

        $autoValidate = AutoValidate::first();
        $autoValidate->pengembalian = $request->autoValidate == '1';
        $autoValidate->save();
        return redirect()->back()->with('message', 'Auto validate pengembalian berhasil diubah.');
    }

    public function transaksiPeminjaman(Request $request)
    {
        $autoValidate = AutoValidate::first();

        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $query = LaporanPeminjaman::where('status_validasi', 'Menunggu');

        $query->orderByRaw("CASE WHEN status_validasi = 'Menunggu' THEN 0 ELSE 1 END");

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

        return view("admin.transaksi.peminjaman.index", compact('laporans', 'search', 'perPage', 'autoValidate'));
    }

    public function validasiPeminjaman(Request $request, $id)
    {
        $request->validate([
            'status_validasi' => 'required|in:Diterima,Ditolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        if ($request->status_validasi == 'Ditolak') {
            $request->validate(
                [
                    'catatan' => 'required|string|max:1000',
                ],
                [
                    'catatan.required' => 'Catatan harus diisi.',
                ]
            );
        }

        $laporan = LaporanPeminjaman::findOrFail($id);

        if ($request->status_validasi == 'Ditolak') {
            $laporan->status_validasi = 'Ditolak';
            $alatList = Alat::whereIn('id', $laporan->alat_id)->get();
            foreach ($alatList as $alat) {
                $alat->status = 'Tersedia';
                $alat->save();
            }
            $laporan->catatan = $request->catatan;
            $laporan->updated_at = now();
            $laporan->save();
            return redirect()->back()->with('message', 'Peminjaman ditolak.');
        }

        $laporan->status_validasi = $request->status_validasi;
        $laporan->catatan = $request->catatan;
        $laporan->updated_at = now();
        $laporan->save();

        return redirect()->back()->with('message', 'Validasi peminjaman berhasil dilakukan.');
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
            $request->validate(
                [
                    'catatan' => 'required|string|max:1000',
                ],
                [
                    'catatan.required' => 'Catatan harus diisi.',
                ]
            );
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
            $request->validate(
                [
                    'deskripsi_kerusakan' => 'required|string|max:1000',
                ],
                [
                    'deskripsi_kerusakan.required' => 'Deskripsi kerusakan harus diisi.',
                ]
            );
        }

        $laporan->status_pengembalian = 'Sudah Dikembalikan';
        $laporan->tgl_pengembalian = now();
        $laporan->kondisi_setelah = $request->kondisi_setelah;
        $laporan->catatan = $request->catatan;
        $laporan->updated_at = now();

        $laporan->save();

        if ($request->kondisi_setelah === 'Rusak' && $laporan->alat !== null) {
            $laporan->deskripsi_kerusakan = $request->deskripsi_kerusakan;
            $laporan->alat->condition = 'Rusak';
            $laporan->alat->status = 'Maintenance';
            $laporan->alat->save();
        }

        if ($laporan->status_pengembalian === 'Sudah Dikembalikan' && $laporan->alat !== null) {
            $laporan->alat->status = 'Tersedia';
            $laporan->alat->save();
        }

        if ($laporan->status_pengembalian === 'Sudah Dikembalikan' && $laporan->ruangan !== null) {
            $laporan->ruangan->status = 'Tersedia';
            $laporan->ruangan->save();
        }

        return redirect()->back()->with('message', 'Validasi pengembalian berhasil.');
    }
}
