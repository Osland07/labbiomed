<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KunjunganExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $collection = [];

        $query = Kunjungan::with('ruangan')->orderBy('waktu_masuk', 'desc');
        
        // Apply same filters as controller
        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim_nip', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhereHas('ruangan', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($this->request->filled('date_from')) {
            $query->whereDate('waktu_masuk', '>=', $this->request->date_from);
        }
        
        if ($this->request->filled('date_to')) {
            $query->whereDate('waktu_masuk', '<=', $this->request->date_to);
        }
        
        if ($this->request->filled('status')) {
            if ($this->request->status === 'active') {
                $query->whereNull('waktu_keluar');
            } elseif ($this->request->status === 'completed') {
                $query->whereNotNull('waktu_keluar');
            }
        }
        
        if ($this->request->filled('ruangan_id')) {
            $query->where('ruangan_id', $this->request->ruangan_id);
        }
        
        $kunjungans = $query->get();

        $no = 1;
        foreach ($kunjungans as $kunjungan) {
            $durasi = $kunjungan->waktu_keluar 
                ? Carbon::parse($kunjungan->waktu_masuk)->diffInMinutes(Carbon::parse($kunjungan->waktu_keluar))
                : Carbon::parse($kunjungan->waktu_masuk)->diffInMinutes(now());

            $collection[] = [
                'No' => $no++,
                'Ruangan' => $kunjungan->ruangan->name ?? '-',
                'Nama' => $kunjungan->nama,
                'NIM/NIP' => $kunjungan->nim_nip ?? '-',
                'Instansi' => $kunjungan->instansi ?? '-',
                'Tujuan' => $kunjungan->tujuan,
                'Waktu Masuk' => Carbon::parse($kunjungan->waktu_masuk)->format('d/m/Y H:i:s'),
                'Waktu Keluar' => $kunjungan->waktu_keluar ? Carbon::parse($kunjungan->waktu_keluar)->format('d/m/Y H:i:s') : '-',
                'Status' => $kunjungan->waktu_keluar ? 'Selesai' : 'Aktif',
                'Durasi (menit)' => $durasi,
            ];
        }

        array_unshift($collection, ['Laporan Kunjungan Laboratorium'], ['']);

        return collect($collection);
    }

    public function headings(): array
    {
        return [
            [''],
            [
                'No',
                'Ruangan',
                'Nama',
                'NIM/NIP',
                'Instansi',
                'Tujuan',
                'Waktu Masuk',
                'Waktu Keluar',
                'Status',
                'Durasi (menit)',
            ],
        ];
    }
} 