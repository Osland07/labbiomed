<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Detail Aktivitas Mahasiswa
    </x-slot>

    <!-- Back Button -->
    <x-slot name="formCreate">
        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </x-slot>

    <!-- Student Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <img class="img-fluid rounded" 
                         src="{{ asset('assets/profile/' . ($mahasiswa->profile_photo_path ?? 'default.png')) }}" 
                         alt="{{ $mahasiswa->name }}" width="120">
                </div>
                <div class="col-md-10">
                    <h4 class="card-title">{{ $mahasiswa->name }}</h4>
                    <p class="card-text text-muted">{{ $mahasiswa->email }}</p>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <strong>NIM:</strong> {{ $mahasiswa->nim ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>Program Studi:</strong> {{ $mahasiswa->prodi ?? '-' }}
                        </div>
                        <div class="col-md-4">
                            <strong>No. HP:</strong> {{ $mahasiswa->no_hp ?? '-' }}
                        </div>
                    </div>
                    @php
                        $judulPenelitian = $peminjaman->first()->judul_penelitian ?? ($penggunaanAlat->first()->tujuan_penggunaan ?? null);
                    @endphp
                    @if($judulPenelitian)
                        <div class="mb-2">
                            <strong>Judul Penelitian/Kegiatan:</strong> {{ $judulPenelitian }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
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
            <div class="small-box bg-success">
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
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistik['total_peminjaman'] }}</h3>
                    <p>Peminjaman</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $statistik['kunjungan_hari_ini'] + $statistik['penggunaan_hari_ini'] }}</h3>
                    <p>Aktivitas Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="activityTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="kunjungan-tab" data-toggle="tab" href="#kunjungan" role="tab" aria-controls="kunjungan" aria-selected="true">
                        <i class="fas fa-calendar-check mr-2"></i>Kunjungan ({{ $kunjungan->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="penggunaan-tab" data-toggle="tab" href="#penggunaan" role="tab" aria-controls="penggunaan" aria-selected="false">
                        <i class="fas fa-tools mr-2"></i>Penggunaan Alat ({{ $penggunaanAlat->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="peminjaman-tab" data-toggle="tab" href="#peminjaman" role="tab" aria-controls="peminjaman" aria-selected="false">
                        <i class="fas fa-file-alt mr-2"></i>Peminjaman ({{ $peminjaman->count() }})
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="activityTabContent">
                <!-- Kunjungan Tab -->
                <div class="tab-pane fade show active" id="kunjungan" role="tabpanel" aria-labelledby="kunjungan-tab">
                    @if($kunjungan->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
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
                                            <td>{{ \Carbon\Carbon::parse($kunjunganItem->waktu_masuk)->format('d/m/Y') }}</td>
                                            <td>{{ $kunjunganItem->ruangan->name ?? '-' }}</td>
                                            <td>{{ Str::limit($kunjunganItem->tujuan, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kunjunganItem->waktu_masuk)->format('H:i') }}</td>
                                            <td>{{ $kunjunganItem->waktu_keluar ? \Carbon\Carbon::parse($kunjunganItem->waktu_keluar)->format('H:i') : '-' }}</td>
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
                            <h5 class="text-muted">Tidak ada kunjungan</h5>
                            <p class="text-muted">Mahasiswa belum melakukan kunjungan ke laboratorium.</p>
                        </div>
                    @endif
                </div>

                <!-- Penggunaan Alat Tab -->
                <div class="tab-pane fade" id="penggunaan" role="tabpanel" aria-labelledby="penggunaan-tab">
                    @if($penggunaanAlat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
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
                                            <td>{{ \Carbon\Carbon::parse($penggunaan->waktu_mulai)->format('d/m/Y') }}</td>
                                            <td>{{ $penggunaan->alat->name ?? '-' }}</td>
                                            <td>{{ Str::limit($penggunaan->tujuan_penggunaan, 50) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($penggunaan->waktu_mulai)->format('H:i') }}</td>
                                            <td>{{ $penggunaan->waktu_selesai ? \Carbon\Carbon::parse($penggunaan->waktu_selesai)->format('H:i') : '-' }}</td>
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
                            <h5 class="text-muted">Tidak ada penggunaan alat</h5>
                            <p class="text-muted">Mahasiswa belum menggunakan alat laboratorium.</p>
                        </div>
                    @endif
                </div>

                <!-- Peminjaman Tab -->
                <div class="tab-pane fade" id="peminjaman" role="tabpanel" aria-labelledby="peminjaman-tab">
                    @if($peminjaman->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pengajuan</th>
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
                                            <td>{{ \Carbon\Carbon::parse($peminjamanItem->created_at)->format('d/m/Y') }}</td>
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
                            <h5 class="text-muted">Tidak ada peminjaman</h5>
                            <p class="text-muted">Mahasiswa belum mengajukan peminjaman alat.</p>
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