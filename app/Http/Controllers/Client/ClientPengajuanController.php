<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LaporanPeminjaman;
use App\Models\User;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\AutoValidate;

class ClientPengajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:pengajuan-peminjaman-client')->only(['index']);
    }

    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $anggotas = User::role('Mahasiswa')->get();
        $dosens = User::role('Dosen')->get();

        // Get all alat for compacting/grouping in the view
        $alats = Alat::where('status', 'Tersedia')->get();

        if ($search) {
            $ruangans = Ruangan::where('name', 'like', "%{$search}%")
                ->paginate($validPerPage);
        } else {
            $ruangans = Ruangan::paginate($validPerPage);
        }

        return view("client.pengajuan-peminjaman.index", compact('alats', 'ruangans', 'anggotas', 'dosens', 'search', 'perPage'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'perPage' => 'nullable|integer|in:10,50,100',
        ]);

        $search = $request->input('search');
        $perPage = (int) $request->input('perPage', 10);

        $validPerPage = in_array($perPage, [10, 50, 100]) ? $perPage : 10;

        $query = LaporanPeminjaman::where('user_id', Auth::user()->id)->where('status_validasi', 'Menunggu')->orderBy('updated_at', 'desc');

        $query->orderByRaw("CASE WHEN status_validasi = 'Menunggu' THEN 0 ELSE 1 END");

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

        return view("client.pengajuan-peminjaman.upload", compact('laporans', 'search', 'perPage'));
    }

    public function storeUpload(Request $request, $id)
    {
        $laporan = LaporanPeminjaman::findOrFail($id);

        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/surat', $filename);
            $laporan->surat = $filename;

            // Auto validate if enabled
            $autoValidate = AutoValidate::first();
            if ($autoValidate && $autoValidate->peminjaman) {
                $laporan->status_validasi = 'Diterima';
            }
            $laporan->save();
        }

        return redirect()->back()->with('message', 'Surat berhasil diupload.');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'jenis' => 'required|in:pribadi,kelompok',
                'tujuan_peminjaman' => 'required|string',
                'judul_penelitian' => 'required|string',
                'dosen_pembimbing' => 'nullable|exists:users,id',
                'tgl_peminjaman' => 'required|date',
                'tgl_pengembalian' => 'required|date|after_or_equal:tgl_peminjaman',
                'daftar_anggota' => 'nullable|string',
                'daftar_alat' => 'required|string',
            ],
            [
                'jenis.required' => 'Jenis peminjaman harus diisi.',
                'tujuan_peminjaman.required' => 'Tujuan peminjaman harus diisi.',
                'judul_penelitian.required' => 'Judul penelitian / Kegiatan harus diisi.',
                'dosen_pembimbing.required' => 'Dosen pembimbing harus dipilih.',
                'dosen_pembimbing.exists' => 'Dosen pembimbing tidak ditemukan.',
                'tgl_peminjaman.required' => 'Tanggal peminjaman harus diisi.',
                'tgl_peminjaman.date' => 'Tanggal peminjaman harus berupa tanggal.',
                'tgl_pengembalian.required' => 'Tanggal pengembalian harus diisi.',
                'tgl_pengembalian.date' => 'Tanggal pengembalian harus berupa tanggal.',
                'tgl_pengembalian.after_or_equal' => 'Tanggal pengembalian harus setelah tanggal peminjaman.',
                'daftar_alat.required' => 'Daftar alat harus diisi.',
            ]
        );

        // daftar_alat is now a flat array of alat IDs
        $alatIds = json_decode($request->daftar_alat, true);

        $laporan = LaporanPeminjaman::create([
            'user_id' => auth()->id(),
            'dosen_id' => $request->dosen_pembimbing,
            'jenis_peminjaman' => $request->jenis,
            'judul_penelitian' => $request->judul_penelitian,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'alat_id' => $alatIds,
            'status_validasi' => 'Menunggu',
            'status_kegiatan' => 'Sedang Berjalan',
        ]);

        if ($request->jenis === 'kelompok' && $request->daftar_anggota) {
            $anggotaList = json_decode($request->daftar_anggota, true);
            foreach ($anggotaList as $anggotaName) {
                $user = User::where('name', $anggotaName)->first();
                if ($user) {
                    $laporan->anggotas()->attach($user->id);
                }
            }
        }

        return redirect()->route('client.pengajuan-peminjaman.upload')->with('message', 'Peminjaman berhasil diajukan.');
    }

    public function generateFormulir($id)
    {
        $laporan = LaporanPeminjaman::findOrFail($id);
        $user = Auth::user();

        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $formatTanggalIndo = function ($date) use ($bulanIndo) {
            $tanggal = $date->format('j');
            $bulan = $bulanIndo[(int)$date->format('n')];
            $tahun = $date->format('Y');
            return "$tanggal $bulan $tahun";
        };

        $tanggalHariIni = 'Lampung Selatan, ' . $formatTanggalIndo(now());
        $tanggalPeminjaman = $formatTanggalIndo(new \Carbon\Carbon($laporan->tgl_peminjaman));
        $tanggalPengembalian = $formatTanggalIndo(new \Carbon\Carbon($laporan->tgl_pengembalian));

        $keperluan = $laporan->tujuan_peminjaman ?? '-';
        $tempatKegiatan = 'Laboratorium Teknik Biomedis';
        $judulPenelitian = $laporan->judul_penelitian ?? '-';
        $dosenPembimbing = $laporan->dosen ? $laporan->dosen->name : '-';

        $alatDipinjam = [];
        $alatList = $laporan->alatList();
        // Group alat by base name and count
        $groupedAlat = [];
        foreach ($alatList as $alat) {
            $baseName = preg_replace('/\\s+#\\d+$/', '', $alat->name);
            if (!isset($groupedAlat[$baseName])) {
                $groupedAlat[$baseName] = 0;
            }
            $groupedAlat[$baseName]++;
        }
        foreach ($groupedAlat as $name => $qty) {
            $alatDipinjam[] = [
                'nama' => $name,
                'jumlah' => $qty,
                'kondisi_sebelum' => 'Baik',
                'tgl_pengembalian' => $tanggalPengembalian,
            ];
        }

        $pdf = Pdf::loadView('client.pengajuan-peminjaman.pdf.index', compact(
            'user',
            'tanggalHariIni',
            'keperluan',
            'tempatKegiatan',
            'tanggalPeminjaman',
            'tanggalPengembalian',
            'judulPenelitian',
            'dosenPembimbing',
            'alatDipinjam',
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('Formulir-Peminjaman-Alat.pdf');
    }
}
