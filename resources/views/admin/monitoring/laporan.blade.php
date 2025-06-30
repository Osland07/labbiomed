<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Laporan Aktivitas Mahasiswa
    </x-slot>

    <!-- Back Button -->
    <x-slot name="formCreate">
        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </x-slot>

    <!-- Filter Form -->
    <x-slot name="search">
        <form method="GET" action="{{ route('admin.monitoring.laporan') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Periode</label>
                    <select name="tanggal" id="tanggal" class="form-control" onchange="this.form.submit()">
                        <option value="hari_ini" {{ $filterTanggal === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="minggu_ini" {{ $filterTanggal === 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ $filterTanggal === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="semester_ini" {{ $filterTanggal === 'semester_ini' ? 'selected' : '' }}>Semester Ini</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="mahasiswa" class="form-label">Mahasiswa</label>
                    <select name="mahasiswa" id="mahasiswa" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ $filterMahasiswa === 'all' ? 'selected' : '' }}>Semua Mahasiswa</option>
                        @foreach($mahasiswaBimbingan as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" {{ $filterMahasiswa == $mahasiswa->id ? 'selected' : '' }}>
                                {{ $mahasiswa->name }} ({{ $mahasiswa->nim ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </x-slot>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistik['total_mahasiswa'] }}</h3>
                    <p>Total Mahasiswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistik['total_kunjungan'] }}</h3>
                    <p>Total Kunjungan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistik['total_penggunaan_alat'] }}</h3>
                    <p>Penggunaan Alat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $statistik['total_peminjaman'] }}</h3>
                    <p>Peminjaman</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Mahasiswa Teraktif -->
    @if($statistik['mahasiswa_teraktif'])
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-trophy mr-2 text-warning"></i>Mahasiswa Teraktif
            </h5>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center">
                <img class="img-circle mr-3" 
                     src="{{ asset('assets/profile/' . ($statistik['mahasiswa_teraktif']->profile_photo_path ?? 'default.png')) }}" 
                     alt="{{ $statistik['mahasiswa_teraktif']->name }}" width="60" height="60">
                <div>
                    <h6 class="mb-1">{{ $statistik['mahasiswa_teraktif']->name }}</h6>
                    <p class="text-muted mb-0">{{ $statistik['mahasiswa_teraktif']->nim ?? 'N/A' }} - {{ $statistik['mahasiswa_teraktif']->prodi ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Activity Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="activityTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="kunjungan-tab" data-toggle="tab" href="#kunjungan" role="tab">
                        <i class="fas fa-calendar-check mr-2"></i>Kunjungan ({{ $kunjungan->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="penggunaan-tab" data-toggle="tab" href="#penggunaan" role="tab">
                        <i class="fas fa-tools mr-2"></i>Penggunaan Alat ({{ $penggunaanAlat->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="peminjaman-tab" data-toggle="tab" href="#peminjaman" role="tab">
                        <i class="fas fa-file-alt mr-2"></i>Peminjaman ({{ $peminjaman->count() }})
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="activityTabContent">
                <!-- Kunjungan Tab -->
                <div class="tab-pane fade show active" id="kunjungan" role="tabpanel">
                    @if($kunjungan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Ruangan</th>
                                        <th>Tujuan</th>
                                        <th>Waktu Masuk</th>
                                        <th>Waktu Keluar</th>
                                        <th>Durasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kunjungan as $index => $kunjunganItem)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="img-circle mr-2" 
                                                         src="{{ asset('assets/profile/' . ($kunjunganItem->user->profile_photo_path ?? 'default.png')) }}" 
                                                         alt="{{ $kunjunganItem->user->name }}" width="30" height="30">
                                                    <div>
                                                        <div class="font-weight-bold">{{ $kunjunganItem->user->name }}</div>
                                                        <small class="text-muted">{{ $kunjunganItem->user->nim ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $kunjunganItem->ruangan->name ?? '-' }}</td>
                                            <td>{{ Str::limit($kunjunganItem->tujuan, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kunjunganItem->waktu_masuk)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $kunjunganItem->waktu_keluar ? \Carbon\Carbon::parse($kunjunganItem->waktu_keluar)->format('d/m/Y H:i') : '-' }}</td>
                                            <td>
                                                @if($kunjunganItem->waktu_keluar)
                                                    @php
                                                        $masuk = \Carbon\Carbon::parse($kunjunganItem->waktu_masuk);
                                                        $keluar = \Carbon\Carbon::parse($kunjunganItem->waktu_keluar);
                                                        $durasi = $masuk->diffInMinutes($keluar);
                                                    @endphp
                                                    {{ floor($durasi / 60) }}j {{ $durasi % 60 }}m
                                                @else
                                                    <span class="badge badge-success">Sedang di dalam</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data kunjungan</h5>
                            <p class="text-muted">Tidak ada kunjungan dalam periode yang dipilih.</p>
                        </div>
                    @endif
                </div>

                <!-- Penggunaan Alat Tab -->
                <div class="tab-pane fade" id="penggunaan" role="tabpanel">
                    @if($penggunaanAlat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Alat</th>
                                        <th>Tujuan</th>
                                        <th>Waktu Mulai</th>
                                        <th>Waktu Selesai</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penggunaanAlat as $index => $penggunaan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="img-circle mr-2" 
                                                         src="{{ asset('assets/profile/' . ($penggunaan->user->profile_photo_path ?? 'default.png')) }}" 
                                                         alt="{{ $penggunaan->user->name }}" width="30" height="30">
                                                    <div>
                                                        <div class="font-weight-bold">{{ $penggunaan->user->name }}</div>
                                                        <small class="text-muted">{{ $penggunaan->user->nim ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $penggunaan->alat->name ?? '-' }}</td>
                                            <td>{{ Str::limit($penggunaan->tujuan_penggunaan, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($penggunaan->waktu_mulai)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $penggunaan->waktu_selesai ? \Carbon\Carbon::parse($penggunaan->waktu_selesai)->format('d/m/Y H:i') : '-' }}</td>
                                            <td>{{ $penggunaan->durasi_penggunaan }}</td>
                                            <td>
                                                @if($penggunaan->status_penggunaan === 'Selesai')
                                                    <span class="badge badge-success">Selesai</span>
                                                @elseif($penggunaan->status_penggunaan === 'Sedang Berjalan')
                                                    <span class="badge badge-info">Sedang Berjalan</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $penggunaan->status_penggunaan }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data penggunaan alat</h5>
                            <p class="text-muted">Tidak ada penggunaan alat dalam periode yang dipilih.</p>
                        </div>
                    @endif
                </div>

                <!-- Peminjaman Tab -->
                <div class="tab-pane fade" id="peminjaman" role="tabpanel">
                    @if($peminjaman->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Mahasiswa</th>
                                        <th>Judul Penelitian</th>
                                        <th>Dosen Pembimbing</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Alat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($peminjaman as $index => $peminjamanItem)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="img-circle mr-2" 
                                                         src="{{ asset('assets/profile/' . ($peminjamanItem->user->profile_photo_path ?? 'default.png')) }}" 
                                                         alt="{{ $peminjamanItem->user->name }}" width="30" height="30">
                                                    <div>
                                                        <div class="font-weight-bold">{{ $peminjamanItem->user->name }}</div>
                                                        <small class="text-muted">{{ $peminjamanItem->user->nim ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ Str::limit($peminjamanItem->judul_penelitian, 50) }}</td>
                                            <td>{{ $peminjamanItem->dosen->name ?? '-' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($peminjamanItem->tgl_peminjaman)->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($peminjamanItem->tgl_pengembalian)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if($peminjamanItem->status_validasi === 'Diterima')
                                                    <span class="badge badge-success">Diterima</span>
                                                @elseif($peminjamanItem->status_validasi === 'Ditolak')
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @elseif($peminjamanItem->status_validasi === 'Menunggu Laboran')
                                                    <span class="badge badge-warning">Menunggu Laboran</span>
                                                @elseif($peminjamanItem->status_validasi === 'Menunggu Koordinator')
                                                    <span class="badge badge-info">Menunggu Koordinator</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $peminjamanItem->status_validasi }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $alatList = $peminjamanItem->alatList();
                                                @endphp
                                                @if($alatList->count() > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($alatList->take(2) as $alat)
                                                            <span class="badge badge-light">{{ $alat->name }}</span>
                                                        @endforeach
                                                        @if($alatList->count() > 2)
                                                            <span class="badge badge-secondary">+{{ $alatList->count() - 2 }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data peminjaman</h5>
                            <p class="text-muted">Tidak ada peminjaman dalam periode yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var triggerTabList = [].slice.call(document.querySelectorAll('#activityTabs a'));
                triggerTabList.forEach(function (triggerEl) {
                    triggerEl.addEventListener('click', function (event) {
                        event.preventDefault();
                        var tab = new bootstrap.Tab(triggerEl);
                        tab.show();
                    });
                });
            });
        </script>
    </x-slot>

</x-admin-table>