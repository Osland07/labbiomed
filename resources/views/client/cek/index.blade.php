<x-admin-layout>
    <x-slot name="title">Cek Ketersediaan</x-slot>

    <div class="mb-5">
        {{-- Filter Form --}}
        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3 mb-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
                <div class="col-md-1">
                    <select name="perPage" class="form-select">
                        <option value="12" {{ request('perPage') == 12 ? 'selected' : '' }}>12</option>
                        <option value="60" {{ request('perPage') == 60 ? 'selected' : '' }}>60</option>
                        <option value="120" {{ request('perPage') == 120 ? 'selected' : '' }}>120</option>
                    </select>
                </div>

                {{-- Dropdown pilih jenis data --}}
                <div class="col-md-2 mb-4">
                    <select name="type" class="form-select">
                        <option value="alat" {{ request('type', 'alat') == 'alat' ? 'selected' : '' }}>Alat</option>
                        @if (!auth()->user()->hasRole('Mahasiswa'))
                            <option value="bahan" {{ request('type') == 'bahan' ? 'selected' : '' }}>Bahan</option>
                        @endif
                        <option value="ruangan" {{ request('type') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                </div>
            </div>
        </form>

        @if (request('type', 'alat') === 'alat')
            {{-- Alat Section --}}
            <h4 class="mt-4 mb-3">Alat</h4>
            <div class="row">
                @foreach ($alats as $groupName => $items)
                    <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-4">
                        <div class="card h-100">
                            @if ($items->first()->img)
                                <img src="{{ asset('storage/' . $items->first()->img) }}" class="card-img-top"
                                    alt="{{ $groupName }}"
                                    style="height: 160px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 4px solid #fff;">
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" class="card-img-top" alt="Default"
                                    style="height: 160px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 4px solid #fff;">
                            @endif
                            <div class="card-body py-2">
                                <h5 class="card-title fw-bold text-primary">{{ Str::limit($groupName, 25, '...') }}</h5>
                                <p class="card-text m-0 p-0 text-muted">{{ $items->first()->ruangan->name }}</p>
                                <p class="card-text m-0 p-0">Total: {{ $items->count() }}</p>

                                {{-- Tombol untuk membuka modal --}}
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalDetail-{{ \Str::slug($groupName) }}">Lihat</a>

                                {{-- Modal --}}
                                <div class="modal fade" id="modalDetail-{{ \Str::slug($groupName) }}" tabindex="-1"
                                    role="dialog" aria-labelledby="modalLabel-{{ \Str::slug($groupName) }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <div>
                                                    <h5 class="modal-title mb-0" id="modalLabel-{{ \Str::slug($groupName) }}">
                                                        <i class="bi bi-info-circle me-2"></i>Detail Informasi {{ $groupName }}
                                                    </h5>
                                                    <small class="text-white-50">Informasi lengkap dan status terkini alat laboratorium</small>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                @php
                                                    $total = $items->count();
                                                    $jumlah_baik = $items->where('condition', 'Baik')->count();
                                                    $jumlah_rusak = $items->where('condition', 'Rusak')->count();
                                                    $jumlah_tersedia = $items->where('status', 'Tersedia')->count();
                                                    $jumlah_dipinjam = $items->where('status', 'Dipinjam')->count();
                                                    $firstAlat = $items->first();
                                                @endphp

                                                <!-- Header dengan gambar dan informasi utama -->
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <img src="{{ $firstAlat->img ? asset('storage/' . $firstAlat->img) : asset('assets/img/default.png') }}" 
                                                                 class="img-fluid rounded shadow" 
                                                                 style="max-height: 200px; object-fit: cover;" 
                                                                 alt="{{ $groupName }}">
                                                            <!-- Tombol Cek Riwayat -->
                                                            <div class="mt-3">
                                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                                        data-bs-target="#modalRiwayatAlat-{{ \Str::slug($groupName) }}">
                                                                    <i class="bi bi-clock-history me-2"></i>Cek Riwayat
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h4 class="text-primary mb-3">{{ $groupName }}</h4>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <p class="mb-2"><strong>Kategori:</strong> {{ $firstAlat->category->name ?? 'Tidak ada kategori' }}</p>
                                                                <p class="mb-2"><strong>Lokasi:</strong> {{ $firstAlat->ruangan->name ?? 'Tidak ditentukan' }}</p>
                                                                <p class="mb-2"><strong>Detail Lokasi:</strong> {{ $firstAlat->detail_location ?? 'Tidak ada detail' }}</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p class="mb-2"><strong>Sumber:</strong> {{ $firstAlat->source ?? 'Tidak diketahui' }}</p>
                                                                <p class="mb-2"><strong>Tanggal Diterima:</strong> {{ $firstAlat->date_received ? \Carbon\Carbon::parse($firstAlat->date_received)->format('d/m/Y') : 'Tidak diketahui' }}</p>
                                                            </div>
                                                        </div>
                                                        @if($firstAlat->desc)
                                                            <div class="mt-3">
                                                                <strong>Deskripsi:</strong><br>
                                                                <p class="text-muted">{{ $firstAlat->desc }}</p>
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
                                                            <div class="col-md-6">
                                                                <div class="card bg-info text-white text-center">
                                                                    <div class="card-body py-3">
                                                                        <h4 class="mb-0">{{ $jumlah_tersedia }}</h4>
                                                                        <small>Tersedia</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card bg-warning text-dark text-center">
                                                                    <div class="card-body py-3">
                                                                        <h4 class="mb-0">{{ $jumlah_dipinjam }}</h4>
                                                                        <small>Dipinjam</small>
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
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($items as $index => $alat)
                                                                        <tr>
                                                                            <td>{{ $index + 1 }}</td>
                                                                            <td><strong>{{ $alat->name }}</strong></td>
                                                                            <td><code>{{ $alat->serial_number ?? 'Tidak ada' }}</code></td>
                                                                            <td>
                                                                                @if ($alat->condition == 'Baik')
                                                                                    <span class="badge bg-success">Baik</span>
                                                                                @else
                                                                                    <span class="badge bg-danger">Rusak</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @if ($alat->status == 'Tersedia')
                                                                                    <span class="badge bg-primary">Tersedia</span>
                                                                                @elseif ($alat->status == 'Dipinjam')
                                                                                    <span class="badge bg-warning text-dark">Dipinjam</span>
                                                                                @elseif ($alat->status == 'Maintenance')
                                                                                    <span class="badge bg-info text-dark">Maintenance</span>
                                                                                @elseif ($alat->status == 'Rusak')
                                                                                    <span class="badge bg-danger">Rusak</span>
                                                                                @else
                                                                                    <span class="badge bg-secondary">{{ $alat->status }}</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $alat->ruangan->name ?? '-' }}</td>
                                                                            <td>
                                                                                <img src="{{ $alat->img ? asset('storage/' . $alat->img) : asset('assets/img/default.png') }}" 
                                                                                     class="img-thumbnail" 
                                                                                     style="width: 50px; height: 50px; object-fit: cover;" 
                                                                                     alt="{{ $alat->name }}">
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" 
                                                                                        data-bs-target="#modalRiwayatAlat-{{ $alat->id }}">
                                                                                    <i class="bi bi-clock-history me-1"></i>Riwayat
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Riwayat Alat --}}
                                @foreach ($items as $alat)
                                    <div class="modal fade" id="modalRiwayatAlat-{{ $alat->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="modalRiwayatLabel-{{ $alat->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <div>
                                                        <h5 class="modal-title mb-0" id="modalRiwayatLabel-{{ $alat->id }}">
                                                            <i class="bi bi-clock-history me-2"></i>Riwayat Penggunaan {{ $alat->name }}
                                                        </h5>
                                                        <small class="text-white-50">Serial Number: {{ $alat->serial_number ?? 'Tidak ada' }}</small>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    @php
                                                        $riwayatAlat = \App\Models\Laporan::where('alat_id', $alat->id)
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
                                                            <small class="text-muted">Alat dengan serial number {{ $alat->serial_number ?? 'Tidak ada' }} belum pernah digunakan</small>
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
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $alats->withQueryString()->links() }}
            </div>
        @endif

        @if (!auth()->user()->hasRole('Mahasiswa'))
            @if (request('type') === 'bahan')
                {{-- Bahan Section --}}
                <h4 class="mt-5 mb-3">Bahan</h4>
                <div class="row">
                    @forelse ($bahans as $bahan)
                        <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-4">
                            <div class="card h-100">
                                @if ($bahan->img)
                                    <img src="{{ asset('storage/' . $bahan->img) }}" class="card-img-top"
                                        alt="{{ $bahan->name }}">
                                @else
                                    <img src="{{ asset('assets/img/default.png') }}" class="card-img-top"
                                        alt="Default">
                                @endif
                                <div class="card-body py-2">
                                    <h5 class="card-title fw-bold text-primary">{{ Str::limit($bahan->name, 25, '...') }}</h5>
                                    <p class="card-text m-0 p-0">Stok: <span
                                            class="badge bg-success">{{ $bahan->stock }}
                                            {{ $bahan->unit }}</span></p>
                                    <p class="card-text m-0 p-0 text-muted">Kadaluarsa: {{ $bahan->date_expired }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada data bahan.</p>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $bahans->withQueryString()->links() }}
                </div>
            @endif
        @endif

        @if (request('type') === 'ruangan')
            {{-- Ruangan Section --}}
            <h4 class="mt-5 mb-3">Ruangan</h4>
            <div class="row">
                @forelse ($ruangans as $ruangan)
                    <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-4">
                        <div class="card h-100">
                            @if ($ruangan->foto_ruangan)
                                <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}" class="card-img-top"
                                    alt="{{ $ruangan->name }}">
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" class="card-img-top"
                                    alt="Default">
                            @endif
                            <div class="card-body py-2">
                                <h5 class="card-title fw-bold text-primary">{{ Str::limit($ruangan->name, 25, '...') }}</h5>
                                <p class="card-text m-0 p-0 text-muted">Gedung: {{ $ruangan->gedung }}</p>
                                <p class="card-text m-0 p-0 text-muted">Lantai: {{ $ruangan->lantai }}</p>
                                <p class="card-text m-0 p-0">Kapasitas: <span
                                        class="badge bg-success">{{ $ruangan->kapasitas }}</span></p>
                                
                                {{-- Tombol untuk membuka modal --}}
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalDetailRuangan-{{ $ruangan->id }}">Lihat</a>

<<<<<<< HEAD
                                    {{-- Modal --}}
=======
                                {{-- Modal --}}
>>>>>>> ba1dda8b55349036508095fa9b94fad972557b98
                                <div class="modal fade" id="modalDetailRuangan-{{ $ruangan->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="modalLabelRuangan-{{ $ruangan->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <div>
                                                    <h5 class="modal-title mb-0" id="modalLabelRuangan-{{ $ruangan->id }}">
                                                        <i class="bi bi-building me-2"></i>Detail Informasi {{ $ruangan->name }}
                                                    </h5>
                                                    <small class="text-white-50">Informasi lengkap dan status terkini ruangan laboratorium</small>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <!-- Header dengan gambar dan informasi utama -->
                                                <div class="row mb-4">
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <img src="{{ $ruangan->foto_ruangan ? asset('storage/' . $ruangan->foto_ruangan) : asset('assets/img/default.png') }}" 
                                                                 class="img-fluid rounded shadow" 
                                                                 style="max-height: 200px; object-fit: cover;" 
                                                                 alt="{{ $ruangan->name }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <h4 class="text-primary mb-3">{{ $ruangan->name }}</h4>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <p class="mb-2"><strong>Gedung:</strong> {{ $ruangan->gedung }}</p>
                                                                <p class="mb-2"><strong>Lantai:</strong> {{ $ruangan->lantai }}</p>
                                                                <p class="mb-2"><strong>Kapasitas:</strong> {{ $ruangan->kapasitas }} orang</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p class="mb-2"><strong>Status:</strong> 
                                                                    @if ($ruangan->status == 'Tersedia')
                                                                        <span class="badge bg-success">Tersedia</span>
                                                                    @elseif ($ruangan->status == 'Digunakan')
                                                                        <span class="badge bg-warning text-dark">Digunakan</span>
                                                                    @elseif ($ruangan->status == 'Maintenance')
                                                                        <span class="badge bg-info text-dark">Maintenance</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">{{ $ruangan->status }}</span>
                                                                    @endif
                                                                </p>
                                                                <p class="mb-2"><strong>Kategori:</strong> {{ $ruangan->category->name ?? 'Tidak ada kategori' }}</p>
                                                                @if($ruangan->serial_number)
                                                                    <p class="mb-2"><strong>Serial Number:</strong> <code>{{ $ruangan->serial_number }}</code></p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if($ruangan->keterangan)
                                                            <div class="mt-3">
                                                                <strong>Keterangan:</strong><br>
                                                                <p class="text-muted">{{ $ruangan->keterangan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Kunjungan Aktif -->
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h5 class="border-bottom pb-2 mb-3">
                                                            <i class="bi bi-people me-2"></i>Kunjungan Aktif
                                                        </h5>
                                                        @php
                                                            $kunjunganAktif = \App\Models\Kunjungan::where('ruangan_id', $ruangan->id)
                                                                ->whereNull('waktu_keluar')
                                                                ->whereDate('waktu_masuk', \Carbon\Carbon::today())
                                                                ->get();
                                                        @endphp
                                                        
                                                        @if($kunjunganAktif->count() > 0)
                                                            <div class="row g-3">
                                                                @foreach($kunjunganAktif as $kunjungan)
                                                                    <div class="col-md-6">
                                                                        <div class="card border-primary">
                                                                            <div class="card-body">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="flex-shrink-0">
                                                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                                                             style="width: 50px; height: 50px;">
                                                                                            <i class="bi bi-person"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1 ms-3">
                                                                                        <h6 class="mb-1">{{ $kunjungan->nama ?? $kunjungan->user->name ?? 'Tidak diketahui' }}</h6>
                                                                                        <p class="mb-1 text-muted small">
                                                                                            @if($kunjungan->nim_nip)
                                                                                                {{ $kunjungan->nim_nip }}
                                                                                            @elseif($kunjungan->user && $kunjungan->user->nim_nip)
                                                                                                {{ $kunjungan->user->nim_nip }}
                                                                                            @else
                                                                                                -
                                                                                            @endif
                                                                                        </p>
                                                                                        <p class="mb-0 text-muted small">
                                                                                            <i class="bi bi-clock me-1"></i>
                                                                                            Masuk: {{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('H:i') }}
                                                                                        </p>
                                                                                        @if($kunjungan->tujuan)
                                                                            <p class="mb-0 text-muted small">
                                                                                <i class="bi bi-info-circle me-1"></i>
                                                                                {{ Str::limit($kunjungan->tujuan, 50) }}
                                                                            </p>
                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <div class="text-center py-4">
                                                                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                                                <p class="text-muted mt-2">Tidak ada kunjungan aktif saat ini</p>
                                                                <small class="text-muted">Ruangan ini sedang kosong</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Statistik Kunjungan -->
                                                <div class="row mb-4">
                                                    <div class="col-12">
                                                        <h5 class="border-bottom pb-2 mb-3">
                                                            <i class="bi bi-graph-up me-2"></i>Statistik Kunjungan Hari Ini
                                                        </h5>
                                                        @php
                                                            $totalKunjunganHariIni = \App\Models\Kunjungan::where('ruangan_id', $ruangan->id)
                                                                ->whereDate('waktu_masuk', \Carbon\Carbon::today())
                                                                ->count();
                                                            $kunjunganMasihDiDalam = $kunjunganAktif->count();
                                                            $kunjunganSudahKeluar = $totalKunjunganHariIni - $kunjunganMasihDiDalam;
                                                        @endphp
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <div class="card bg-primary text-white text-center">
                                                                    <div class="card-body py-3">
                                                                        <h4 class="mb-0">{{ $totalKunjunganHariIni }}</h4>
                                                                        <small>Total Kunjungan Hari Ini</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="card bg-success text-white text-center">
                                                                    <div class="card-body py-3">
                                                                        <h4 class="mb-0">{{ $kunjunganMasihDiDalam }}</h4>
                                                                        <small>Sedang di Laboratorium</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="card bg-info text-white text-center">
                                                                    <div class="card-body py-3">
                                                                        <h4 class="mb-0">{{ $kunjunganSudahKeluar }}</h4>
                                                                        <small>Selesai Kunjungan</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Denah Ruangan -->
                                                @if ($ruangan->foto_denah)
                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <h5 class="border-bottom pb-2 mb-3">
                                                                <i class="bi bi-map me-2"></i>Denah Ruangan
                                                            </h5>
                                                            <div class="text-center">
                                                                <img src="{{ asset('storage/' . $ruangan->foto_denah) }}"
                                                                    alt="Denah {{ $ruangan->name }}" class="img-fluid rounded shadow"
                                                                    style="max-height: 300px; object-fit: cover;">
                                                            </div>
                                                        </div>
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
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada data ruangan.</p>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $ruangans->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
