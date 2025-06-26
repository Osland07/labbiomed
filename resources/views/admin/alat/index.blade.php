<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Alat
    </x-slot>

    <x-slot name="formCreate">
        <form method="GET" class="d-flex align-items-center me-3">
            <label class="me-2">{{ __('Status') }}:</label>
            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                <option value="">Semua</option>
                <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Hilang" {{ request('status') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>

            <input type="hidden" name="view" value="{{ request('view', 'compact') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
        </form>

        <form method="GET" class="d-flex align-items-center me-3">
            <label class="me-2">{{ __('Tampilan') }}:</label>
            <select name="view" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                <option value="compact" {{ request('view') == 'compact' ? 'selected' : '' }}>Compact</option>
                <option value="detail" {{ request('view') == 'detail' ? 'selected' : '' }}>Detail</option>
            </select>

            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
        </form>

        @can('create-alat')
            @include('admin.alat.create')
        @endcan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    @if (request('view') == 'detail')
        <!-- Tampilan Detail -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Serial Number</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Tanggal Diterima</th>
                    <th>Sumber</th>
                    <th>Status</th>
                    <th>Lokasi</th>
                    <th>Gambar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alats as $alat)
                    <tr>
                        <td>{{ $loop->iteration + ($alats->currentPage() - 1) * $alats->perPage() }}</td>
                        <td>{{ $alat->name }}</td>
                        <td>{{ $alat->serial_number }}</td>
                        <td>{{ $alat->category->name ?? '-' }}</td>
                        <td>
                            @if ($alat->condition == 'Baik')
                                <span class="badge bg-success">{{ $alat->condition }}</span>
                            @elseif ($alat->condition == 'Rusak')
                                <span class="badge bg-danger">{{ $alat->condition }}</span>
                            @else
                                <span class="badge bg-warning">{{ $alat->condition }}</span>
                            @endif
                        </td>
                        <td>{{ $alat->date_received ?? '-' }}</td>
                        <td>{{ $alat->source ?? '-' }}</td>
                        <td>
                            @if ($alat->status == 'Tersedia')
                                <span class="badge bg-primary">{{ $alat->status }}</span>
                            @else
                                <span class="badge bg-warning">{{ $alat->status }}</span>
                            @endif
                        </td>
                        <td>{{ $alat->ruangan->name ?? '-' }}</td>
                        <td>
                            @if ($alat->img)
                                <img src="{{ asset('storage/' . $alat->img) }}" alt="Gambar" width="100">
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" alt="Default" width="100">
                            @endif
                        </td>
                        <td class="text-center">
                            @can('edit-alat')
                                @include('admin.alat.edit')
                            @endcan
                            @can('delete-alat')
                                @include('admin.alat.delete')
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <!-- Tampilan Compact -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Tanggal Diterima</th>
                    <th>Sumber</th>
                    <th>Lokasi</th>
                    <th>Jumlah Total</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alat_groups as $key => $group)
                    <tr>
                        <td>{{ $loop->iteration + ($alat_groups->currentPage() - 1) * $alat_groups->perPage() }}</td>
                        <td>{{ $key }}</td>
                        <td>{{ $group->first()->category->name ?? '-' }}</td>
                        <td>{{ $group->first()->date_received ?? '-' }}</td>
                        <td>{{ $group->first()->source ?? '-' }}</td>
                        <td>{{ $group->first()->ruangan->name ?? '-' }}</td>
                        <td>{{ $group->count() }}</td>
                        <td class="manage-row text-center">
                            <!-- Tombol Lihat atau Detail -->
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalDetail{{ \Illuminate\Support\Str::slug($key, '-') }}">Lihat</a>

                            <!-- Modal -->
                            <div class="modal fade" id="modalDetail{{ \Illuminate\Support\Str::slug($key, '-') }}"
                                tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $key }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @php
                                                $total = $group->count();
                                                $jumlah_baik = $group->where('condition', 'Baik')->count();
                                                $jumlah_rusak = $group->where('condition', 'Rusak')->count();
                                                $jumlah_tersedia = $group->where('status', 'Tersedia')->count();
                                            @endphp

                                            <!-- Tambahkan ringkasan jumlah -->
                                            <div class="mb-3">
                                                <strong>Ringkasan:</strong>
                                                <p class="m-0 p-0">Total Alat: <strong>{{ $total }}</strong></p>
                                                <p class="m-0 p-0">Jumlah Baik: <span
                                                        class="text-success"><strong>{{ $jumlah_baik }}</strong></span>
                                                </p>
                                                <p class="m-0 p-0">Jumlah Rusak: <span
                                                        class="text-danger"><strong>{{ $jumlah_rusak }}</strong></span>
                                                </p>
                                                <p class="m-0 p-0">Jumlah Tersedia: <span
                                                        class="text-primary"><strong>{{ $jumlah_tersedia }}</strong></span>
                                                </p>
                                            </div>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Serial Number</th>
                                                        <th>Kondisi</th>
                                                        <th>Status</th>
                                                        <th>Gambar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($group as $item)
                                                        <tr>
                                                            <td>{{ $item->serial_number }}</td>
                                                            <td>
                                                                @if ($item->condition == 'Baik')
                                                                    <span class="badge bg-success">{{ $item->condition }}
                                                                    </span>
                                                                @elseif ($item->condition == 'Rusak')
                                                                    <span class="badge bg-danger">{{ $item->condition }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning">{{ $item->condition }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($item->status == 'Tersedia')
                                                                    <span class="badge bg-primary">{{ $item->status }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning">{{ $item->status }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($item->img)
                                                                    <img src="{{ asset('storage/' . $item->img) }}"
                                                                        alt="Gambar" width="100">
                                                                @else
                                                                    <img src="{{ asset('assets/img/default.png') }}"
                                                                        alt="Default" width="100">
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- Pagination -->
    @if ($view === 'detail')
        {{ $alats->appends(['perPage' => $perPage, 'search' => $search, 'view' => $view])->links() }}
    @else
        {{ $alat_groups->appends(['perPage' => $perPage, 'search' => $search, 'view' => $view])->links() }}
    @endif

</x-admin-table>
