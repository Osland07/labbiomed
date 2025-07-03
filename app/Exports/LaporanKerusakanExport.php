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
                'Status Penggantian' => $item->is_replaced ? 'Sudah Diganti' : 'Belum Diganti',
                'Tanggal Penggantian' => $item->replaced_at ? date('d-m-Y H:i', strtotime($item->replaced_at)) : '-',
                'Validator' => $item->replaced_by ? optional(\App\Models\User::find($item->replaced_by))->name : '-',
                'Catatan Penggantian' => $item->replace_note ?? '-',
                'Bukti Penggantian' => $item->replace_image ? asset('storage/' . $item->replace_image) : '-',
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
                'Status Penggantian',
                'Tanggal Penggantian',
                'Validator',
                'Catatan Penggantian',
                'Bukti Penggantian',
            ],
        ];
    }
}
