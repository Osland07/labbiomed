<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index()
    {
        $kunjungans = Kunjungan::orderBy('waktu_masuk', 'desc')->paginate(20);
        return view('admin.kunjungan.index', compact('kunjungans'));
    }
} 