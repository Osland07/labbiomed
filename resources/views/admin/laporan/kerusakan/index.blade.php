<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Laporan Kerusakan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Button Export -->
    <x-slot name="export">
        @include('admin.laporan.kerusakan.export')
    </x-slot>

    <!-- Table -->
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th>{{ __('Pengguna') }}</th>
                <th>{{ __('Nama Alat/Bahan/Ruangan') }}</th>
                <th>{{ __('Nomor Seri') }}</th>
                <th>{{ __('Tanggal Kerusakan') }}</th>
                <th>{{ __('Deskripsi Kerusakan') }}</th>
                <th>{{ __('Gambar Kerusakan') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td style="max-width: 220px; word-wrap: break-word; white-space: normal;">
                        <span>{{ $laporan->user->name ?? '-' }}</span>
                        / {{ $laporan->user->nim ?? '-' }} /
                        <a href="https://wa.me/+62{{ $laporan->user->no_hp ?? '-' }}">
                            {{ $laporan->user->no_hp ?? '-' }}
                            <i class="fa fa-whatsapp text-success"></i></a> / <a
                            href="mailto:{{ $laporan->user->email ?? '-' }}">{{ $laporan->user->email ?? '-' }} <i
                                class="fa fa-envelope text-primary"></i></a>
                    </td>
                    <td>{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}</td>
                    <td>{{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}
                    </td>
                    <td>{{ $laporan->tgl_kerusakan ?? '-' }}</td>
                    <td>{{ $laporan->deskripsi_kerusakan ?? '-' }}</td>
                    <td>
                        @if ($laporan->gambar_setelah)
                            <a href="{{ asset('storage/' . $laporan->gambar_setelah) }}" target="_blank">
                                <img src="{{ asset('storage/' . $laporan->gambar_setelah) }}" width="100">
                            </a>
                        @else
                            <img src="{{ asset('assets/img/default.png') }}" alt="Default" width="100">
                        @endif
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
