<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Jadwal
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
                <th>{{ __('Nama Pemesan') }}</th>
                <th>{{ __('Nama Ruang') }}</th>
                <th>{{ __('Keterangan') }}</th>
                <th>{{ __('Tanggal Pemesanan') }}</th>
                <th>{{ __('Jam Mulai') }}</th>
                <th>{{ __('Jam Selesai') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td>{{ $laporan->user->name ?? '-' }}</td>
                    <td>{{ $laporan->ruangan->name ?? '-' }}</td>
                    <td>{{ $laporan->tujuan_penggunaan ?? '-' }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($laporan->waktu_mulai)->translatedFormat('d F Y') ?? '-' }}
                        -
                        {{ \Carbon\Carbon::parse($laporan->waktu_mulai)->translatedFormat('d F Y') ?? '-' }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($laporan->waktu_mulai)->format('H:i') ?? '-' }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($laporan->waktu_selesai)->format('H:i') ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
