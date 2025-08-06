<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Laporan Penggunaan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        <!-- Dihilangkan, search masuk ke filter utama -->
    </x-slot>

    <!-- Button filter & filter2 dihapus, semua filter di collapse saja -->
    <x-slot name="filter"></x-slot>
    <x-slot name="filter2"></x-slot>

    <!-- Button Export -->
    <x-slot name="export">
        <!-- Export di pojok dihapus, hanya di dalam filter -->
    </x-slot>

    <!-- Filter Section (Collapse) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-2"></i>Filter & Pencarian
            </h6>
            @php
                $filterCount = collect([
                    request('search'), request('jenis'), request('filter'), request('filter2'), request('status'), request('date_from'), request('date_to')
                ])->filter()->count();
            @endphp
            <button class="btn btn-sm btn-outline-primary position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="{{ $filterCount ? 'true' : 'false' }}">
                <i class="fas fa-chevron-down"></i>
                @if($filterCount)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $filterCount }}</span>
                @endif
            </button>
        </div>
        <div class="collapse {{ $filterCount ? 'show' : '' }}" id="filterCollapse">
            <div class="card-body">
                <form id="filterForm" method="GET" class="row row-cols-2 row-cols-md-2 row-cols-lg-4 g-2 align-items-end mb-0">
                    <div class="col mb-2">
                        <label class="form-label">Pencarian</label>
                        <input type="text" class="form-control" name="search" placeholder="Cari nama, alat, tujuan..." value="{{ request('search') }}">
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Jenis</label>
                        <select class="form-select" name="jenis">
                            <option value="">Semua Jenis</option>
                            <option value="alat" {{ request('jenis') == 'alat' ? 'selected' : '' }}>Alat</option>
                            <option value="bahan" {{ request('jenis') == 'bahan' ? 'selected' : '' }}>Bahan</option>
                            <option value="ruangan" {{ request('jenis') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Pengguna</label>
                        <select class="form-select" name="filter">
                            <option value="">Semua Pengguna</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('filter') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->nim }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Alat/Bahan/Ruangan</label>
                        <select class="form-select" name="filter2">
                            <option value="">Semua Item</option>
                            @foreach ($items as $item)
                                @php
                                    $jenis = request('jenis');
                                    $show = true;
                                    if ($jenis == 'alat' && !isset($item['alat'])) $show = false;
                                    if ($jenis == 'bahan' && !isset($item['bahan'])) $show = false;
                                    if ($jenis == 'ruangan' && !isset($item['ruangan'])) $show = false;
                                @endphp
                                @if ($show)
                                    <option value="{{ $item }}" {{ request('filter2') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua</option>
                            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col mb-2">
                        <div class="d-flex flex-row gap-2" style="width:100%;">
                            <div style="width:50%;">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div style="width:50%;">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col mb-2">
                        <label class="form-label">Tampilkan</label>
                        <select class="form-select" name="perPage">
                            <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="w-100"></div>
                    <div class="col-12 mb-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" type="submit">Terapkan</button>
                    </div>
                    <div class="col-12 mb-2 d-flex align-items-end">
                        <a href="{{ route('admin.laporan.penggunaan') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                    <div class="col-12 mb-2 d-flex align-items-end">
                        @include('admin.laporan.penggunaan.export')
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ringkasan Filter Aktif -->
    @php
        $activeFilters = [];
        if(request('search')) $activeFilters[] = 'Cari: "'.request('search').'"';
        if(request('jenis')) $activeFilters[] = 'Jenis: '.ucfirst(request('jenis'));
        if(request('filter')) {
            $user = $users->where('id', request('filter'))->first();
            if($user) $activeFilters[] = 'Pengguna: '.$user->name.' ('.$user->nim.')';
        }
        if(request('filter2')) $activeFilters[] = 'Item: '.request('filter2');
        if(request('status')) $activeFilters[] = 'Status: '.request('status');
        if(request('date_from')) $activeFilters[] = 'Dari: '.request('date_from');
        if(request('date_to')) $activeFilters[] = 'Sampai: '.request('date_to');
    @endphp
    @if(count($activeFilters))
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <div>
                <strong>Filter Aktif:</strong> {!! implode(', ', $activeFilters) !!}
            </div>
            <a href="{{ route('admin.laporan.penggunaan') }}" class="btn btn-sm btn-outline-secondary">Reset Filter</a>
        </div>
    @endif

    <!-- Table -->
    <div class="table-responsive">
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th class="sticky-col">{{ __('Pengguna') }}</th>
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
                    <td class="sticky-col" style="max-width: 220px; word-wrap: break-word; white-space: normal;">
                        <span>{{ $laporan->user->name ?? '-' }}</span>
                        <br> {{ $laporan->user->nim ?? '-' }}<br>
                        <a href="https://wa.me/+62{{ $laporan->user->no_hp ?? '-' }}">
                            {{ $laporan->user->no_hp ?? '-' }}
                            <i class="fa fa-whatsapp text-success"></i></a><br> <a
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
                    <td>{{ $laporan->tgl_pengembalian ?? 'Sedang Digunakan' }}</td>
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
    </div>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>

<style>
    /* Sticky column for Pengguna */
    .sticky-col {
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 2;
        border-right: 1px solid #dee2e6;
    }
    .table thead .sticky-col {
        z-index: 3;
    }
    /* Optional: make table horizontally scrollable on mobile */
    .table-responsive {
        overflow-x: auto;
        width: 100%;
    }
</style>
