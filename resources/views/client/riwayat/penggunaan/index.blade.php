<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Riwayat Penggunaan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Table -->
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th>{{ __('Tujuan Penggunaan') }}</th>
                <th>{{ __('Nama Alat/Ruangan') }}</th>
                <th>{{ __('Nomor Seri') }}</th>
                <th>{{ __('Waktu Mulai') }}</th>
                <th>{{ __('Waktu Selesai') }}</th>
                <th>{{ __('Durasi Penggunaan') }}</th>
                <th>{{ __('Waktu Pengembalian') }}</th>
                <th>{{ __('Status Penggunaan') }}</th>
                <th>{{ __('Kondisi Setelah Penggunaan') }}</th>
                <th>{{ __('Catatan') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td>{{ $laporan->tujuan_penggunaan ?? '-' }}</td>
                    <td>{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}</td>
                    <td>
                        @if ($laporan->status_peminjaman == 'Diterima')
                            {{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $laporan->waktu_mulai ?? '-' }}</td>
                    <td>{{ $laporan->waktu_selesai ?? '-' }}</td>
                    <td>{{ $laporan->durasi_penggunaan ?? '-' }}</td>
                    <td>{{ $laporan->tgl_pengembalian ?? '-' }}</td>
                    <td>
                        @if ($laporan->status_peminjaman == 'Diterima')
                            <span class="badge badge-success">Diterima</span>
                        @elseif ($laporan->status_peminjaman == 'Menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif ($laporan->status_peminjaman == 'Ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->kondisi_setelah == 'Baik')
                            <span class="badge bg-success">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                        @elseif($laporan->kondisi_setelah == 'Rusak')
                            <span class="badge bg-danger">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                        @else
                            <span class="badge bg-warning">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                        @endif
                    </td>
                    <td>{{ $laporan->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
