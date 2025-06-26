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
        return view('kunjungan.checkin', compact('ruangan', 'user'));
    }

    // Proses check-in
    public function storeCheckin(Request $request, $ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        $user = Auth::user();
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
            $request->validate([
                'nama' => 'required|string|max:255',
                'nim_nip' => 'nullable|string|max:255',
                'instansi' => 'nullable|string|max:255',
                'tujuan' => 'required|string|max:255',
            ]);
            $data['nama'] = $request->nama;
            $data['nim_nip'] = $request->nim_nip;
            $data['instansi'] = $request->instansi;
            $data['tujuan'] = $request->tujuan;
        }
        Kunjungan::create($data);
        return redirect()->route('kunjungan.checkin.success', $ruangan->id);
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
        if ($kunjungan) {
            $kunjungan->waktu_keluar = Carbon::now();
            $kunjungan->save();
            return redirect()->route('kunjungan.checkout.success', $ruangan->id);
        }
        return back()->withErrors(['msg' => 'Data kunjungan tidak ditemukan atau sudah checkout.']);
    }

    // Halaman sukses check-in
    public function checkinSuccess($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        return back()->with('message', 'Berhasil Check-in' . $ruangan->nama);
       
    }

    // Halaman sukses check-out
    public function checkoutSuccess($ruangan_id)
    {
        $ruangan = Ruangan::findOrFail($ruangan_id);
        return back()->with('message', 'Berhasil Check-in' . $ruangan->nama);
    }
} 