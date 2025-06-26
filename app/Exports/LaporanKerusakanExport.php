<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKerusakanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $collection = [];

        $no = 1;
        $data = Laporan::where('kondisi_setelah', 'Rusak')->get();

        foreach ($data as $item) {
            $collection[] = [
                'No' => $no++,
                'Pengguna' => $item->user->name ?? '-',
                'Nama Alat/Bahan/Ruangan' => $item->alat->name ?? $item->bahan->name ?? $item->ruangan->name ?? '-',
                'Nomor Seri' => $item->alat->serial_number ?? $item->bahan->serial_number ?? $item->ruangan->serial_number ?? '-',
                'Tanggal Kerusakan' => $item->tgl_kerusakan ?? '-',
                'Deskripsi Kerusakan' => $item->deskripsi_kerusakan ?? '-',
            ];
        }

        array_unshift($collection, ['Laporan Kerusakan'], ['']);

        return collect($collection);
    }

    public function headings(): array
    {
        return [
            [''],
            [
                'No',
                'Pengguna',
                'Nama Alat/Bahan/Ruangan',
                'Nomor Seri',
                'Tanggal Kerusakan',
                'Deskripsi Kerusakan',
            ],
        ];
    }
}
