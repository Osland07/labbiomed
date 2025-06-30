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

        $query = LaporanPeminjaman::where('user_id', Auth::user()->id)
            ->where(function($q) {
                $q->whereIn('status_validasi', [
                    LaporanPeminjaman::STATUS_MENUNGGU_LABORAN,
                    LaporanPeminjaman::STATUS_MENUNGGU_KOORDINATOR,
                    LaporanPeminjaman::STATUS_DITERIMA,
                    LaporanPeminjaman::STATUS_DITOLAK
                ])
                ->orWhereNull('status_validasi')
                ->orWhere('status_validasi', '');
            })
            ->whereNull('surat')
            ->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('judul_penelitian', 'like', "%{$search}%");
        }

        $laporans = $query->paginate($validPerPage);

        return view("client.pengajuan-peminjaman.upload", compact('laporans', 'search', 'perPage'));
    }

    public function storeUpload(Request $request, $id)
    {
        $laporan = LaporanPeminjaman::findOrFail($id);

        // Hanya bisa upload surat jika status sudah diterima
        if (!$laporan->canUploadSurat()) {
            return redirect()->back()->with('error', 'Surat hanya dapat diupload setelah pengajuan diterima.');
        }

        if ($request->hasFile('surat')) {
            $file = $request->file('surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/surat', $filename);
            $laporan->surat = $filename;
            $laporan->status_validasi = LaporanPeminjaman::STATUS_SELESAI;
            $laporan->save();
        }

        return redirect()->back()->with('message', 'Surat berhasil diupload. Data telah dipindahkan ke halaman Riwayat Pengajuan.');
    }

    public function store(Request $request)
    {
        // Cek apakah user mahasiswa
        $isMahasiswa = auth()->user()->hasRole('Mahasiswa');
        $isDosen = auth()->user()->hasRole('Dosen');

        $rules = [
            'jenis' => 'required|in:pribadi,kelompok',
            'tujuan_peminjaman' => 'required|string',
            'judul_penelitian' => 'required|string',
            'tgl_peminjaman' => 'required|date',
            'tgl_pengembalian' => 'required|date|after_or_equal:tgl_peminjaman',
            'daftar_anggota' => 'nullable|string',
            'daftar_alat' => 'required|string',
        ];
        $messages = [
            'jenis.required' => 'Jenis peminjaman harus diisi.',
            'tujuan_peminjaman.required' => 'Keperluan peminjaman harus diisi.',
            'judul_penelitian.required' => 'Judul penelitian / Kegiatan harus diisi.',
            'tgl_peminjaman.required' => 'Tanggal peminjaman harus diisi.',
            'tgl_peminjaman.date' => 'Tanggal peminjaman harus berupa tanggal.',
            'tgl_pengembalian.required' => 'Tanggal pengembalian harus diisi.',
            'tgl_pengembalian.date' => 'Tanggal pengembalian harus berupa tanggal.',
            'tgl_pengembalian.after_or_equal' => 'Tanggal pengembalian harus setelah tanggal peminjaman.',
            'daftar_alat.required' => 'Daftar alat harus diisi.',
        ];
        // Jika mahasiswa, dosen pembimbing wajib
        if ($isMahasiswa) {
            $rules['dosen_pembimbing'] = 'required|exists:users,id';
            $messages['dosen_pembimbing.required'] = 'Dosen pembimbing harus dipilih.';
            $messages['dosen_pembimbing.exists'] = 'Dosen pembimbing tidak ditemukan.';
        } else {
            $rules['dosen_pembimbing'] = 'nullable|exists:users,id';
        }

        $validated = $request->validate($rules, $messages);

        // daftar_alat is now a flat array of alat IDs
        $alatIds = json_decode($request->daftar_alat, true);
        $alatIdsSorted = $alatIds;
        sort($alatIdsSorted);
        $alatIdsJson = json_encode($alatIdsSorted);

        // Jika dosen, pastikan dosen_pembimbing null
        $dosenPembimbing = null;
        if ($isMahasiswa) {
            $dosenPembimbing = $request->dosen_pembimbing;
        }

        // CEK DUPLIKASI DATA
        $duplikat = LaporanPeminjaman::where('user_id', auth()->id())
            ->where('judul_penelitian', $request->judul_penelitian)
            ->where('tujuan_peminjaman', $request->tujuan_peminjaman)
            ->where('tgl_peminjaman', $request->tgl_peminjaman)
            ->where('tgl_pengembalian', $request->tgl_pengembalian)
            ->where(function($q) {
                $q->whereNotIn('status_validasi', [LaporanPeminjaman::STATUS_DITOLAK, LaporanPeminjaman::STATUS_SELESAI])
                  ->orWhereNull('status_validasi');
            })
            ->get()
            ->filter(function($item) use ($alatIdsJson) {
                $itemAlat = $item->alat_id;
                if (is_array($itemAlat)) {
                    $itemAlatSorted = $itemAlat;
                    sort($itemAlatSorted);
                    return json_encode($itemAlatSorted) === $alatIdsJson;
                }
                return false;
            })
            ->first();

        if ($duplikat) {
            return redirect()->back()->withInput()->withErrors(['duplikasi' => 'Pengajuan dengan data yang sama sudah pernah diajukan dan masih dalam proses.']);
        }

        $laporan = LaporanPeminjaman::create([
            'user_id' => auth()->id(),
            'dosen_id' => $dosenPembimbing,
            'jenis_peminjaman' => $request->jenis,
            'judul_penelitian' => $request->judul_penelitian,
            'tujuan_peminjaman' => $request->tujuan_peminjaman,
            'tgl_peminjaman' => $request->tgl_peminjaman,
            'tgl_pengembalian' => $request->tgl_pengembalian,
            'alat_id' => $alatIds,
            'status_validasi' => LaporanPeminjaman::STATUS_MENUNGGU_LABORAN,
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

        return redirect()->route('client.pengajuan-peminjaman.upload')->with('message', 'Peminjaman berhasil diajukan. Menunggu validasi laboran.');
    }

    public function generateFormulir($id)
    {
        $laporan = LaporanPeminjaman::findOrFail($id);
        
        // Hanya bisa generate surat jika sudah diterima
        if (!$laporan->canGenerateSurat()) {
            return redirect()->back()->with('error', 'Surat hanya dapat digenerate setelah pengajuan diterima.');
        }

        $user = Auth::user();

        // Ambil data koordinator laboratorium dari database
        $koordinator = User::role('Koordinator Laboratorium')->first();
        
        // Ambil data laboran dari database
        $laboran = User::role('Laboran')->first();

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
            'koordinator',
            'laboran',
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('Formulir-Peminjaman-Alat.pdf');
    }
}
