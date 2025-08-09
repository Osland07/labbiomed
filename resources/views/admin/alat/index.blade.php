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
                <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>

            <input type="hidden" name="view" value="{{ request('view', 'compact') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
        </form>

        <div class="d-flex align-items-center me-3">
            <label class="me-2">{{ __('Tampilan') }}:</label>
            <div class="btn-group" role="group">
                <a href="{{ request()->fullUrlWithQuery(['view' => 'compact']) }}" 
                   class="btn btn-sm {{ request('view', 'compact') == 'compact' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-th-large me-1"></i>Compact
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view' => 'detail']) }}" 
                   class="btn btn-sm {{ request('view') == 'detail' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-list me-1"></i>Detail
                </a>
            </div>
        </div>

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
                        <td>{{ $alat->date_received ? \Carbon\Carbon::parse($alat->date_received)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $alat->source ?? '-' }}</td>
                        <td>
                            @if ($alat->status == 'Tersedia')
                                <span class="badge bg-primary">{{ $alat->status }}</span>
                            @elseif ($alat->status == 'Dipinjam' || $alat->status == 'Sedang Digunakan')
                                <span class="badge bg-warning text-dark">{{ $alat->status }}</span>
                            @elseif ($alat->status == 'Maintenance')
                                <span class="badge bg-info text-dark">{{ $alat->status }}</span>
                            @elseif ($alat->status == 'Rusak')
                                <span class="badge bg-danger">{{ $alat->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $alat->status }}</span>
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
                        <td colspan="11" class="text-center">Data tidak ditemukan</td>
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
                        <td>{{ $group->first()->date_received ? \Carbon\Carbon::parse($group->first()->date_received)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $group->first()->source ?? '-' }}</td>
                        <td>{{ $group->first()->ruangan->name ?? '-' }}</td>
                        <td>{{ $group->count() }}</td>
                        <td class="manage-row text-center">
                            <!-- Tombol Lihat atau Detail -->
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalDetail{{ \Illuminate\Support\Str::slug($key, '-') }}">Lihat</a>

                            <!-- Modal -->
                            <div class="modal fade" id="modalDetail{{ \Illuminate\Support\Str::slug($key, '-') }}"
                                tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ \Illuminate\Support\Str::slug($key, '-') }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <div>
                                                <h5 class="modal-title mb-0" id="modalLabel{{ \Illuminate\Support\Str::slug($key, '-') }}">
                                                    <i class="bi bi-info-circle me-2"></i>Detail Informasi {{ $key }}
                                                </h5>
                                                <small class="text-white-50">Informasi lengkap dan status terkini alat laboratorium</small>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            @php
                                                $total = $group->count();
                                                $jumlah_baik = $group->where('condition', 'Baik')->count();
                                                $jumlah_rusak = $group->where('condition', 'Rusak')->count();
                                                $jumlah_tersedia = $group->where('status', 'Tersedia')->count();
                                                $jumlah_dipinjam = $group->where('status', 'Dipinjam')->count();
                                                $jumlah_maintenance = $group->where('status', 'Maintenance')->count();
                                                $jumlah_digunakan = $group->where('status', 'Sedang Digunakan')->count();
                                                $firstAlat = $group->first();
                                            @endphp

                                            <!-- Header dengan gambar dan informasi utama -->
                                            <div class="row mb-4">
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <img src="{{ $firstAlat->img ? asset('storage/' . $firstAlat->img) : asset('assets/img/default.png') }}" 
                                                             class="img-fluid rounded shadow" 
                                                             style="max-height: 200px; object-fit: cover;" 
                                                             alt="{{ $key }}">
                                                        <!-- Tombol Cek Riwayat -->
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                                    data-bs-target="#modalRiwayatAlat-{{ \Str::slug($key) }}">
                                                                <i class="bi bi-clock-history me-2"></i>Cek Riwayat
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 text-start">
                                                    <h4 class="text-primary mb-3">{{ $key }}</h4>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <p class="mb-2 text-start"><strong>Kategori:</strong> {{ $firstAlat->category->name ?? 'Tidak ada kategori' }}</p>
                                                            <p class="mb-2 text-start"><strong>Lokasi:</strong> {{ $firstAlat->ruangan->name ?? 'Tidak ditentukan' }}</p>
                                                            <p class="mb-2 text-start"><strong>Detail Lokasi:</strong> {{ $firstAlat->detail_location ?? 'Tidak ada detail' }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="mb-2 text-start"><strong>Sumber:</strong> {{ $firstAlat->source ?? 'Tidak diketahui' }}</p>
                                                            <p class="mb-2 text-start"><strong>Tanggal Diterima:</strong> {{ $firstAlat->date_received ? \Carbon\Carbon::parse($firstAlat->date_received)->format('d/m/Y') : 'Tidak diketahui' }}</p>
                                                        </div>
                                                    </div>
                                                    @if($firstAlat->desc)
                                                        <div class="mt-3 text-start">
                                                            <strong>Deskripsi:</strong><br>
                                                            <p class="text-muted text-start">{{ $firstAlat->desc }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Statistik Ringkasan -->
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <h5 class="border-bottom pb-2 mb-3">
                                                        <i class="bi bi-graph-up me-2"></i>Statistik Alat
                                                    </h5>
                                                    <div class="row g-3">
                                                        <div class="col-md-3">
                                                            <div class="card bg-primary text-white text-center">
                                                                <div class="card-body py-3">
                                                                    <h4 class="mb-0">{{ $total }}</h4>
                                                                    <small>Total</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="card bg-success text-white text-center">
                                                                <div class="card-body py-3">
                                                                    <h4 class="mb-0">{{ $jumlah_tersedia }}</h4>
                                                                    <small>Tersedia</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="card bg-warning text-dark text-center">
                                                                <div class="card-body py-3">
                                                                    <h4 class="mb-0">{{ $jumlah_dipinjam + $jumlah_digunakan }}</h4>
                                                                    <small>Dipinjam</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="card bg-danger text-white text-center">
                                                                <div class="card-body py-3">
                                                                    <h4 class="mb-0">{{ $jumlah_rusak }}</h4>
                                                                    <small>Rusak</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tabel Detail Per Unit -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="border-bottom pb-2 mb-3">
                                                        <i class="bi bi-list-ul me-2"></i>Detail Per Unit
                                                    </h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-striped">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Alat</th>
                                                        <th>Serial Number</th>
                                                        <th>Kondisi</th>
                                                        <th>Status</th>
                                                                    <th>Lokasi</th>
                                                        <th>Gambar</th>
                                                                    <th>Riwayat</th>
                                                                    @can('edit-alat')
                                                                        <th>Aksi</th>
                                                                    @endcan
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                                @foreach ($group as $index => $item)
                                                        <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td><strong>{{ $item->name }}</strong></td>
                                                                        <td><code>{{ $item->serial_number ?? 'Tidak ada' }}</code></td>
                                                            <td>
                                                                @if ($item->condition == 'Baik')
                                                                                <span class="badge bg-success">Baik</span>
                                                                @elseif ($item->condition == 'Rusak')
                                                                                <span class="badge bg-danger">Rusak</span>
                                                                @else
                                                                                <span class="badge bg-warning text-dark">{{ $item->condition }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($item->status == 'Tersedia')
                                                                                <span class="badge bg-primary">Tersedia</span>
                                                                @elseif ($item->status == 'Dipinjam' || $item->status == 'Sedang Digunakan')
                                                                                <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                                                @elseif ($item->status == 'Maintenance')
                                                                                <span class="badge bg-info text-dark">Maintenance</span>
                                                                @elseif ($item->status == 'Rusak')
                                                                                <span class="badge bg-danger">Rusak</span>
                                                                @elseif ($item->status == 'Hilang')
                                                                                <span class="badge bg-dark">Hilang</span>
                                                                            @else
                                                                                <span class="badge bg-secondary">{{ $item->status }}</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $item->ruangan->name ?? '-' }}</td>
                                                                        <td>
                                                                            <img src="{{ $item->img ? asset('storage/' . $item->img) : asset('assets/img/default.png') }}" 
                                                                                 class="img-thumbnail" 
                                                                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                                                                 alt="{{ $item->name }}">
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                                                    data-bs-target="#modalRiwayatAlat-{{ $item->id }}-detail">
                                                                                <i class="bi bi-clock-history me-1"></i>Riwayat
                                                                            </button>
                                                                        </td>
                                                                        @can('edit-alat')
                                                                            <td>
                                                                                <!-- Edit Button -->
                                                                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal"
                                                                                    data-bs-target="#editModal{{ $item->id }}{{ \Illuminate\Support\Str::slug($key, '-') }}">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                @can('delete-alat')
                                                                                    <!-- Delete Button -->
                                                                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                                                        data-bs-target="#deleteModal{{ $item->id }}{{ \Illuminate\Support\Str::slug($key, '-') }}">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                @endcan
                                                                            </td>
                                                                        @endcan
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Edit dan Delete untuk setiap item --}}
                            @foreach ($group as $item)
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $item->id }}{{ \Illuminate\Support\Str::slug($key, '-') }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.alat.update', $item->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Edit Data') }} - {{ $item->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-left row">
                                                    <!-- Nama Alat -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Nama Alat') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                                            value="{{ old('name', $item->name) }}" required>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Gambar -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Gambar (Opsional)') }}</label>
                                                        <input type="file" class="form-control @error('img') is-invalid @enderror" name="img">
                                                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                                        @error('img')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Kategori -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Kategori') }}<span class="text-danger">*</span></label>
                                                        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" required>
                                                            <option value="">{{ __('Pilih Kategori') }}</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('category_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Lokasi -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Lokasi') }}<span class="text-danger">*</span></label>
                                                        <select name="location" class="form-select @error('location') is-invalid @enderror">
                                                            <option value="">{{ __('Pilih Lokasi') }}</option>
                                                            @foreach ($locations as $location)
                                                                <option value="{{ $location->id }}"
                                                                    {{ old('location', $item->location) == $location->id ? 'selected' : '' }}>
                                                                    {{ $location->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('location')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Detail Lokasi -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Detail Lokasi') }}<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('detail_location') is-invalid @enderror"
                                                            name="detail_location" placeholder="Masukkan Lokasi Alat"
                                                            value="{{ old('detail_location', $item->detail_location) }}" required>
                                                        @error('detail_location')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Tanggal Diterima -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Tanggal Diterima') }}<span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control @error('date_received') is-invalid @enderror"
                                                            name="date_received" value="{{ old('date_received', $item->date_received) }}" required>
                                                        @error('date_received')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Sumber -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Sumber') }}<span class="text-danger">*</span></label>
                                                        <select name="source" class="form-select @error('source') is-invalid @enderror">
                                                            <option value="">{{ __('Pilih Sumber') }}</option>
                                                            @foreach ($sources as $source)
                                                                <option value="{{ $source['id'] }}"
                                                                    {{ old('source', $item->source) == $source['id'] ? 'selected' : '' }}>
                                                                    {{ $source['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('source')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Spesifikasi -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Spesifikasi Alat') }}</label>
                                                        <textarea class="form-control @error('desc') is-invalid @enderror" name="desc" rows="1"
                                                            placeholder="Masukkan Spesifikasi Alat">{{ old('desc', $item->desc) }}</textarea>
                                                        @error('desc')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Kondisi -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Kondisi Alat') }}<span class="text-danger">*</span></label>
                                                        <select name="condition" class="form-select @error('condition') is-invalid @enderror">
                                                            <option value="Baik" {{ old('condition', $item->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                            <option value="Rusak" {{ old('condition', $item->condition) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                            <option value="Maintenance" {{ old('condition', $item->condition) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                        </select>
                                                        @error('condition')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">{{ __('Status Alat') }}<span class="text-danger">*</span></label>
                                                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                                                            <option value="Tersedia" {{ old('status', $item->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                            <option value="Maintenance" {{ old('status', $item->status) == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                                            <option value="Rusak" {{ old('status', $item->status) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                        </select>
                                                        @error('status')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Auto Validasi -->
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">{{ __('Auto Validasi') }}</label>
                                                        <div class="form-check form-switch ms-4">
                                                            <input class="form-check-input" type="checkbox" name="auto_validate"
                                                                value="1" {{ old('auto_validate', $item->auto_validate) == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label">Auto Validasi</label>
                                                        </div>
                                                        @error('auto_validate')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
                                                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                @can('delete-alat')
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $item->id }}{{ \Illuminate\Support\Str::slug($key, '-') }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Hapus Permanen Data') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-left">
                                                    Apakah anda yakin ingin menghapus data <strong>{{ $item->name }}</strong> secara permanen?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.alat.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
                                                        <input type="submit" class="btn btn-danger" value="Hapus">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endcan

                                <!-- Individual Riwayat Modal -->
                                <div class="modal fade" id="modalRiwayatAlat-{{ $item->id }}-detail" tabindex="-1"
                                    role="dialog" aria-labelledby="modalRiwayatLabel-{{ $item->id }}-detail"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <div>
                                                    <h5 class="modal-title mb-0" id="modalRiwayatLabel-{{ $item->id }}-detail">
                                                        <i class="bi bi-clock-history me-2"></i>Riwayat Penggunaan {{ $item->name }}
                                                    </h5>
                                                    <small class="text-white-50">Serial Number: {{ $item->serial_number ?? 'Tidak ada' }}</small>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                @php
                                                    $riwayatAlat = \App\Models\Laporan::where('alat_id', $item->id)
                                                        ->whereNotNull('alat_id')
                                                        ->orderBy('created_at', 'desc')
                                                        ->limit(10)
                                                        ->get();
                                                @endphp

                                                @if($riwayatAlat->count() > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-striped">
                                                            <thead class="table-dark">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Pengguna</th>
                                                                    <th>NIM/NIP</th>
                                                                    <th>Tujuan Penggunaan</th>
                                                                    <th>Waktu Mulai</th>
                                                                    <th>Waktu Selesai</th>
                                                                    <th>Kondisi Setelah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($riwayatAlat as $index => $laporan)
                                                                    <tr>
                                                                        <td>{{ $index + 1 }}</td>
                                                                        <td>
                                                                            <strong>
                                                                                {{ $laporan->user->name ?? 'Tidak diketahui' }}
                                                                            </strong>
                                                                        </td>
                                                                        <td>
                                                                            <code>
                                                                                {{ $laporan->user->nim_nip ?? '-' }}
                                                                            </code>
                                                                        </td>
                                                                        <td>
                                                                            <span title="{{ $laporan->tujuan_penggunaan ?? '-' }}">
                                                                                {{ Str::limit($laporan->tujuan_penggunaan ?? '-', 30) }}
                                                                    </span>
                                                                        </td>
                                                                        <td>
                                                                            <small class="text-muted">
                                                                                {{ $laporan->waktu_mulai ? \Carbon\Carbon::parse($laporan->waktu_mulai)->format('d/m/Y H:i') : '-' }}
                                                                            </small>
                                                                        </td>
                                                                        <td>
                                                                            <small class="text-muted">
                                                                                {{ $laporan->waktu_selesai ? \Carbon\Carbon::parse($laporan->waktu_selesai)->format('d/m/Y H:i') : '-' }}
                                                                            </small>
                                                                        </td>
                                                                        <td>
                                                                            @if($laporan->kondisi_setelah == 'Baik')
                                                                                <span class="badge bg-success">Baik</span>
                                                                            @elseif($laporan->kondisi_setelah == 'Rusak')
                                                                                <span class="badge bg-danger">Rusak</span>
                                                                            @elseif($laporan->kondisi_setelah == 'Sedikit Rusak')
                                                                                <span class="badge bg-warning text-dark">Sedikit Rusak</span>
                                                                @else
                                                                                <span class="badge bg-secondary">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                                                                @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                                                        <p class="text-muted mt-2">Belum ada riwayat penggunaan</p>
                                                        <small class="text-muted">Alat dengan serial number {{ $item->serial_number ?? 'Tidak ada' }} belum pernah digunakan</small>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Modal Riwayat Grup Alat --}}
                            <div class="modal fade" id="modalRiwayatAlat-{{ \Str::slug($key) }}" tabindex="-1"
                                role="dialog" aria-labelledby="modalRiwayatLabelGrup-{{ \Str::slug($key) }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info text-white">
                                            <div>
                                                <h5 class="modal-title mb-0" id="modalRiwayatLabelGrup-{{ \Str::slug($key) }}">
                                                    <i class="bi bi-clock-history me-2"></i>Riwayat Penggunaan {{ $key }}
                                                </h5>
                                                <small class="text-white-50">Riwayat penggunaan semua unit alat {{ $key }}</small>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            @php
                                                $alatIds = $group->pluck('id');
                                                $riwayatGrupAlat = \App\Models\Laporan::whereIn('alat_id', $alatIds)
                                                    ->whereNotNull('alat_id')
                                                    ->with(['user', 'alat'])
                                                    ->orderBy('created_at', 'desc')
                                                    ->limit(20)
                                                    ->get();
                                            @endphp

                                            @if($riwayatGrupAlat->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-striped">
                                                        <thead class="table-dark">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Serial Number</th>
                                                                <th>Nama Pengguna</th>
                                                                <th>NIM/NIP</th>
                                                                <th>Tujuan Penggunaan</th>
                                                                <th>Waktu Mulai</th>
                                                                <th>Waktu Selesai</th>
                                                                <th>Kondisi Setelah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($riwayatGrupAlat as $index => $laporan)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td><code>{{ $laporan->alat->serial_number ?? '-' }}</code></td>
                                                                    <td>
                                                                        <strong>
                                                                            {{ $laporan->user->name ?? 'Tidak diketahui' }}
                                                                        </strong>
                                                                    </td>
                                                                    <td>
                                                                        <code>
                                                                            {{ $laporan->user->nim_nip ?? '-' }}
                                                                        </code>
                                                                    </td>
                                                                    <td>
                                                                        <span title="{{ $laporan->tujuan_penggunaan ?? '-' }}">
                                                                            {{ Str::limit($laporan->tujuan_penggunaan ?? '-', 30) }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <small class="text-muted">
                                                                            {{ $laporan->waktu_mulai ? \Carbon\Carbon::parse($laporan->waktu_mulai)->format('d/m/Y H:i') : '-' }}
                                                                        </small>
                                                                    </td>
                                                                    <td>
                                                                        <small class="text-muted">
                                                                            {{ $laporan->waktu_selesai ? \Carbon\Carbon::parse($laporan->waktu_selesai)->format('d/m/Y H:i') : '-' }}
                                                                        </small>
                                                            </td>
                                                            <td>
                                                                        @if($laporan->kondisi_setelah == 'Baik')
                                                                            <span class="badge bg-success">Baik</span>
                                                                        @elseif($laporan->kondisi_setelah == 'Rusak')
                                                                            <span class="badge bg-danger">Rusak</span>
                                                                        @elseif($laporan->kondisi_setelah == 'Sedikit Rusak')
                                                                            <span class="badge bg-warning text-dark">Sedikit Rusak</span>
                                                                @else
                                                                            <span class="badge bg-secondary">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                                                    <p class="text-muted mt-2">Belum ada riwayat penggunaan</p>
                                                    <small class="text-muted">Semua unit {{ $key }} belum pernah digunakan</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan</td>
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
