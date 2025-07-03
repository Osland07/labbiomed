<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Dashboard Koordinator Laboratorium
    </x-slot>

    @include('components.alert')

    <!-- Content -->
    <div class="row">
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <a href="{{ route('admin.alat.index') }}" class="text-decoration-none">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalAlat }}</h3>
                        <p>Total Alat</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="small-box-footer">
                        Kelola Alat <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <a href="{{ route('admin.bahan.index') }}" class="text-decoration-none">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalBahan }}</h3>
                        <p>Total Bahan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <div class="small-box-footer">
                        Kelola Bahan <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <a href="{{ route('admin.ruangan.index') }}" class="text-decoration-none">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalRuangan }}</h3>
                        <p>Total Ruangan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="small-box-footer">
                        Kelola Ruangan <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalMahasiswa }}</h3>
                    <p>Total Mahasiswa</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $totalDosen }}</h3>
                    <p>Total Dosen</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <a href="{{ route('admin.transaksi.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pengajuanMenungguKoordinator }}</h3>
                        <p>Menunggu Validasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="small-box-footer">
                        Validasi Sekarang <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Statistik 6 Bulan Terakhir
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="statistikChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Aktivitas Hari Ini
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-info"><i class="fas fa-door-open"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kunjungan</span>
                            <span class="info-box-number">{{ $kunjunganHariIni }}</span>
                        </div>
                    </div>
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-success"><i class="fas fa-tools"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Penggunaan Alat</span>
                            <span class="info-box-number">{{ $penggunaanAlatHariIni }}</span>
                        </div>
                    </div>
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-warning"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Penggunaan Ruangan</span>
                            <span class="info-box-number">{{ $penggunaanRuanganHariIni }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Pengajuan Menunggu Validasi
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.transaksi.peminjaman') }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-check"></i> Validasi Sekarang
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Dosen</th>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuanTerbaru as $pengajuan)
                                <tr style="cursor: pointer;" onclick="window.location.href='{{ route('admin.transaksi.peminjaman') }}'">
                                    <td>{{ $pengajuan->user->name }}</td>
                                    <td>{{ $pengajuan->dosen->name ?? '-' }}</td>
                                    <td>{{ Str::limit($pengajuan->judul_penelitian, 25) }}</td>
                                    <td>{{ $pengajuan->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada pengajuan menunggu validasi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Ringkasan Pengajuan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Disetujui</span>
                                    <span class="info-box-number">{{ $pengajuanDisetujui }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ditolak</span>
                                    <span class="info-box-number">{{ $pengajuanDitolak }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt mr-1"></i>
                        Aksi Cepat
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.transaksi.peminjaman') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-check-circle"></i><br>
                                Validasi Peminjaman
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.alat.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-tools"></i><br>
                                Kelola Alat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bahan.index') }}" class="btn btn-success btn-block">
                                <i class="fas fa-flask"></i><br>
                                Kelola Bahan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-file-signature"></i><br>
                                Laporan Peminjaman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Penting
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Dashboard Koordinator Laboratorium</h5>
                        <p>Selamat datang di dashboard khusus koordinator laboratorium. Di sini Anda dapat:</p>
                        <ul>
                            <li>Memantau semua aset laboratorium (alat, bahan, ruangan)</li>
                            <li>Melihat statistik penggunaan laboratorium</li>
                            <li>Memvalidasi pengajuan peminjaman yang sudah disetujui laboran</li>
                            <li>Melihat aktivitas laboratorium secara real-time</li>
                            <li>Memantau jumlah mahasiswa dan dosen yang menggunakan laboratorium</li>
                        </ul>
                        <p>Untuk mengelola aset dan melihat laporan detail, silakan kunjungi menu yang tersedia di sidebar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk chart
        const statistikData = @json($statistikBulanan);
        
        const ctx = document.getElementById('statistikChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: statistikData.map(item => item.bulan),
                datasets: [{
                    label: 'Pengajuan',
                    data: statistikData.map(item => item.pengajuan),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Kunjungan',
                    data: statistikData.map(item => item.kunjungan),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Penggunaan',
                    data: statistikData.map(item => item.penggunaan),
                    borderColor: 'rgb(255, 205, 86)',
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush

    @push('styles')
    <style>
        .small-box {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .small-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .small-box-footer {
            background-color: rgba(0,0,0,0.1);
            color: #fff;
            padding: 8px 15px;
            font-size: 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .info-box {
            transition: all 0.3s ease;
        }
        
        .info-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .btn-block {
            height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-block:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-block i {
            font-size: 24px;
            margin-bottom: 5px;
        }
    </style>
    @endpush

</x-admin-layout> 