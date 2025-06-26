<?php

namespace App\Exports;

use App\Models\LaporanPeminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPeminjamanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $collection = [];

        $no = 1;
        $data = LaporanPeminjaman::all();

        foreach ($data as $item) {
            $tgl_peminjaman = Carbon::parse($item->tgl_peminjaman)->locale('id')->translatedFormat('d F Y');
            $tgl_pengembalian = Carbon::parse($item->tgl_pengembalian)->locale('id')->translatedFormat('d F Y');
            $durasi = "$tgl_peminjaman - $tgl_pengembalian";

            $collection[] = [
                'No' => $no++,
                'Pemohon' => $item->user->name ?? '-',
                'Keperluan' => $item->tujuan_peminjaman ?? '-',
                'Peralatan' => $item->alat->name ?? $item->bahan->name ?? $item->ruangan->name ?? '-',
                'Nomor Seri' => $item->alat->serial_number ?? $item->bahan->serial_number ?? $item->ruangan->serial_number ?? '-',
                'Durasi Kegiatan' => $durasi ?? '-',
                'Status Validasi' => $item->status_validasi ?? '-',
                'Status Kegiatan' => $item->status_kegiatan ?? '-',
                'Catatan Admin' => $item->catatan ?? '-',
            ];
        }

        array_unshift($collection, ['Laporan Peminjaman'], ['']);

        return collect($collection);
    }

    public function headings(): array
    {
        return [
            [''],
            [
                'No',
                'Pemohon',
                'Keperluan',
                'Peralatan',
                'Nomor Seri',
                'Durasi Kegiatan',
                'Status Validasi',
                'Status Kegiatan',
                'Catatan Admin',
            ],
        ];
    }
}
