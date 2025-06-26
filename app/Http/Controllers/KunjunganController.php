<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    // Tampilkan form check-in
    public function showCheckin($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $user = Auth::user();
        
        // Cek apakah user sudah ada kunjungan aktif di ruangan ini
        if ($user) {
            $activeVisit = Kunjungan::where('ruangan_id', $ruangan->id)
                ->where('nama', $user->name)
                ->whereNull('waktu_keluar')
                ->first();
                
            if ($activeVisit) {
                return redirect()->route('kunjungan.checkout', $ruangan->id)
                    ->with('warning', 'Anda sudah melakukan check-in di ruangan ini. Silakan lakukan check-out terlebih dahulu.');
            }
        }
        
        return view('kunjungan.checkin', compact('ruangan', 'user'));
    }

    // Proses check-in
    public function storeCheckin(Request $request, $ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $user = Auth::user();
        
        // Validasi input
        if ($user) {
            $request->validate([
                'tujuan' => 'required|string|max:500',
            ]);
        } else {
            $request->validate([
                'nama' => 'required|string|max:255',
                'nim_nip' => 'nullable|string|max:255',
                'instansi' => 'nullable|string|max:255',
                'tujuan' => 'required|string|max:500',
            ]);
        }
        
        // Cek apakah sudah ada kunjungan aktif
        if ($user) {
            $activeVisit = Kunjungan::where('ruangan_id', $ruangan->id)
                ->where('nama', $user->name)
                ->whereNull('waktu_keluar')
                ->first();
                
            if ($activeVisit) {
                return redirect()->route('kunjungan.checkout', $ruangan->id)
                    ->with('warning', 'Anda sudah melakukan check-in di ruangan ini. Silakan lakukan check-out terlebih dahulu.');
            }
        }
        
        $data = [
            'ruangan_id' => $ruangan->id,
            'waktu_masuk' => Carbon::now(),
        ];
        
        if ($user) {
            $data['nama'] = $user->name;
            $data['nim_nip'] = $user->nim_nip ?? null;
            $data['instansi'] = $user->instansi ?? null;
            $data['tujuan'] = $request->tujuan;
        } else {
            $data['nama'] = $request->nama;
            $data['nim_nip'] = $request->nim_nip;
            $data['instansi'] = $request->instansi;
            $data['tujuan'] = $request->tujuan;
        }
        
        Kunjungan::create($data);
        
        return redirect()->route('kunjungan.checkin.success', $ruangan->id)
            ->with('success', 'Check-in berhasil! Selamat datang di ' . $ruangan->name);
    }

    // Tampilkan form check-out
    public function showCheckout($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $user = Auth::user();
        $kunjungans = null;
        
        if (!$user) {
            // Tampilkan nama-nama yang belum checkout di ruangan ini (hari ini saja)
            $kunjungans = Kunjungan::where('ruangan_id', $ruangan->id)
                ->whereNull('waktu_keluar')
                ->whereDate('waktu_masuk', Carbon::today())
                ->get();
        } else {
            // Cek apakah user memiliki kunjungan aktif
            $activeVisit = Kunjungan::where('ruangan_id', $ruangan->id)
                ->where('nama', $user->name)
                ->whereNull('waktu_keluar')
                ->first();
                
            if (!$activeVisit) {
                return redirect()->route('kunjungan.checkin', $ruangan->id)
                    ->with('info', 'Anda belum melakukan check-in di ruangan ini. Silakan lakukan check-in terlebih dahulu.');
            }
        }
        
        return view('kunjungan.checkout', compact('ruangan', 'user', 'kunjungans'));
    }

    // Proses check-out
    public function storeCheckout(Request $request, $ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $user = Auth::user();
        
        if ($user) {
            $kunjungan = Kunjungan::where('ruangan_id', $ruangan->id)
                ->where('nama', $user->name)
                ->whereNull('waktu_keluar')
                ->latest('waktu_masuk')
                ->first();
        } else {
            $request->validate([
                'kunjungan_id' => 'required|exists:kunjungans,id',
            ]);
            
            $kunjungan = Kunjungan::where('id', $request->kunjungan_id)
                ->where('ruangan_id', $ruangan->id)
                ->whereNull('waktu_keluar')
                ->first();
        }
        
        if (!$kunjungan) {
            return back()->withErrors(['error' => 'Data kunjungan tidak ditemukan atau sudah checkout.']);
        }
        
        // Hitung durasi kunjungan
        $waktuMasuk = Carbon::parse($kunjungan->waktu_masuk);
        $waktuKeluar = Carbon::now();
        $durasi = $waktuMasuk->diffInMinutes($waktuKeluar);
        
        $kunjungan->waktu_keluar = $waktuKeluar;
        $kunjungan->save();
        
        $durasiText = $durasi >= 60 
            ? floor($durasi / 60) . ' jam ' . ($durasi % 60) . ' menit'
            : $durasi . ' menit';
        
        return redirect()->route('kunjungan.checkout.success', $ruangan->id)
            ->with('success', 'Check-out berhasil! Terima kasih telah mengunjungi ' . $ruangan->name . '. Durasi kunjungan: ' . $durasiText);
    }

    // Halaman sukses check-in
    public function checkinSuccess($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        return redirect()->route('beranda')
            ->with('success', 'Check-in berhasil! Selamat datang di ' . $ruangan->name);
    }

    // Halaman sukses check-out
    public function checkoutSuccess($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        return redirect()->route('beranda')
            ->with('success', 'Check-out berhasil! Terima kasih telah mengunjungi ' . $ruangan->name);
<<<<<<< HEAD
    }

    // Generate QR Code untuk Check-in
    public function generateCheckinQR($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $qrData = [
            'type' => 'checkin',
            'ruangan_id' => $ruangan->id,
            'ruangan_name' => $ruangan->name,
            'url' => route('kunjungan.checkin', $ruangan->id),
            'timestamp' => now()->timestamp
        ];
        
        return view('kunjungan.qr-checkin', compact('ruangan', 'qrData'));
    }

    // Generate QR Code untuk Check-out
    public function generateCheckoutQR($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $qrData = [
            'type' => 'checkout',
            'ruangan_id' => $ruangan->id,
            'ruangan_name' => $ruangan->name,
            'url' => route('kunjungan.checkout', $ruangan->id),
            'timestamp' => now()->timestamp
        ];
        
        return view('kunjungan.qr-checkout', compact('ruangan', 'qrData'));
    }

    // Scan QR Code dan redirect
    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string'
        ]);

        $qrData = json_decode($request->qr_data, true);
        
        if (!$qrData || !isset($qrData['type']) || !isset($qrData['ruangan_id'])) {
            return back()->withErrors(['error' => 'QR Code tidak valid.']);
        }

        $ruangan = Ruangan::find($qrData['ruangan_id']);
        if (!$ruangan) {
            return back()->withErrors(['error' => 'Ruangan tidak ditemukan.']);
        }

        if ($qrData['type'] === 'checkin') {
            return redirect()->route('kunjungan.checkin', $ruangan->id);
        } elseif ($qrData['type'] === 'checkout') {
            return redirect()->route('kunjungan.checkout', $ruangan->id);
        }

        return back()->withErrors(['error' => 'Tipe QR Code tidak valid.']);
    }

    // Dashboard Kunjungan
    public function dashboard()
    {
        $ruangans = Ruangan::all();
        $todayVisits = Kunjungan::whereDate('waktu_masuk', Carbon::today())->count();
        $activeVisits = Kunjungan::whereNull('waktu_keluar')->count();
        
        return view('kunjungan.dashboard', compact('ruangans', 'todayVisits', 'activeVisits'));
=======
>>>>>>> beb307dfe502eedce80aab7aef5cf105f5a248be
    }
} 