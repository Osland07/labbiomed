<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanPenggunaanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $collection = [];

        $no = 1;
        $data = Laporan::all();

        foreach ($data as $item) {
            $mulai = Carbon::parse($item->waktu_mulai);
            $selesai = Carbon::parse($item->waktu_selesai);

            $weeks = $mulai->diffInWeeks($selesai);
            $days = $mulai->copy()->addWeeks($weeks)->diffInDays($selesai);
            $hours = $mulai->copy()->addWeeks($weeks)->addDays($days)->diffInHours($selesai);

            $durasi = trim(
                ($weeks ? "$weeks minggu " : '') .
                ($days ? "$days hari " : '') .
                ($hours ? "$hours jam" : '')
            );

            $collection[] = [
                'No' => $no++,
                'Pengguna' => $item->user->name ?? '-',
                'Tujuan Penggunaan' => $item->tujuan_penggunaan ?? '-',
                'Nama Alat/Bahan/Ruangan' => $item->alat->name ?? $item->bahan->name ?? $item->ruangan->name ?? '-',
                'Nomor Seri' => $item->alat->serial_number ?? $item->bahan->serial_number ?? $item->ruangan->serial_number ?? '-',
                'Waktu Mulai' => $item->waktu_mulai ?? '-',
                'Waktu Selesai' => $item->waktu_selesai ?? '-',
                'Durasi Penggunaan' => $durasi ?? '-',
                'Waktu Pengembalian' => $item->tgl_pengembalian ?? '-',
                'Kondisi Sebelum' => $item->kondisi_sebelum ?? '-',
                'Kondisi Setelah' => $item->kondisi_setelah ?? '-',
                'Catatan' => $item->catatan ?? '-',
            ];
        }

        array_unshift($collection, ['Laporan Penggunaan'], ['']);

        return collect($collection);
    }

    public function headings(): array
    {
        return [
            [''],
            [
                'No',
                'Pengguna',
                'Tujuan Penggunaan',
                'Nama Alat/Bahan/Ruangan',
                'Nomor Seri',
                'Waktu Mulai',
                'Waktu Selesai',
                'Durasi Penggunaan',
                'Waktu Pengembalian',
                'Kondisi Sebelum',
                'Kondisi Setelah',
                'Catatan',
            ],
        ];
    }
}
