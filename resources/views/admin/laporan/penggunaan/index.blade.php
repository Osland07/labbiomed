<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Laporan Penggunaan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Button filter -->
    <x-slot name="filter">
        <div class="d-flex justify-items-center">
            <form method="GET" class="me-2">
                <select class="form-select" name="filter" onchange="this.form.submit()">
                    <option value="">Filter by Pengguna</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('filter') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->nim }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <!-- Button filter2 -->
    <x-slot name="filter2">
        <div class="d-flex justify-items-center">
            <form method="GET">
                <select class="form-select" name="filter2" onchange="this.form.submit()">
                    <option value="">Filter by Alat/Bahan/Ruangan</option>
                    @foreach ($items as $item)
                        <option value="{{ $item }}" {{ request('filter2') == $item ? 'selected' : '' }}>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <!-- Button Export -->
    <x-slot name="export">
        @include('admin.laporan.penggunaan.export')
    </x-slot>

    <!-- Table -->
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th>{{ __('Pengguna') }}</th>
                <th>{{ __('Tujuan Penggunaan') }}</th>
                <th>{{ __('Nama Alat/Bahan/Ruangan') }}</th>
                <th>{{ __('Nomor Seri') }}</th>
                <th>{{ __('Waktu Mulai') }}</th>
                <th>{{ __('Waktu Selesai') }}</th>
                <th>{{ __('Durasi Penggunaan') }}</th>
                <th>{{ __('Waktu Pengembalian') }}</th>
                <th>{{ __('Status Peminjaman') }}</th>
                <th>{{ __('Kondisi Setelah Penggunaan') }}</th>
                <th>{{ __('Catatan') }}</th>
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
                    <td>{{ $laporan->tujuan_penggunaan ?? '-' }}</td>
                    <td>{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}</td>
                    <td>{{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}
                    </td>
                    <td>{{ $laporan->waktu_mulai ?? '-' }}</td>
                    <td>{{ $laporan->waktu_selesai ?? '-' }}</td>
                    <td>{{ $laporan->durasi_penggunaan ?? '-' }}</td>
                    <td>{{ $laporan->tgl_pengembalian ?? '-' }}</td>
                    <td>
                        @if ($laporan->status_peminjaman == 'Diterima')
                            <span class="badge bg-success">{{ $laporan->status_peminjaman ?? '-' }}</span>
                        @elseif ($laporan->status_peminjaman == 'Menunggu')
                            <span class="badge bg-warning">{{ $laporan->status_peminjaman ?? '-' }}</span>
                        @elseif ($laporan->status_peminjaman == 'Ditolak')
                            <span class="badge bg-danger">{{ $laporan->status_peminjaman ?? '-' }}</span>
                        @else
                            <span class="badge bg-info">{{ $laporan->status_peminjaman ?? '-' }}</span>
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
                    <td colspan="12" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
