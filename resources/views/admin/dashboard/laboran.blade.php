<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Dashboard Laboran
    </x-slot>

    @include('components.alert')

    <!-- Content -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.transaksi.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pengajuanPending }}</h3>
                        <p>Pengajuan Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="small-box-footer">
                        Validasi Pengajuan <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.transaksi.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $pengajuanDisetujui }}</h3>
                        <p>Pengajuan Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="small-box-footer">
                        Lihat Transaksi <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.transaksi.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $pengajuanSelesai }}</h3>
                        <p>Pengajuan Selesai</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="small-box-footer">
                        Lihat Riwayat <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.transaksi.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $pengajuanDitolak }}</h3>
                        <p>Pengajuan Ditolak</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times"></i>
                    </div>
                    <div class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Statistik Inventaris
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-tools"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Alat</span>
                                    <span class="info-box-number">{{ $totalAlat }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-flask"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Bahan</span>
                                    <span class="info-box-number">{{ $totalBahan }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Ruangan</span>
                                    <span class="info-box-number">{{ $totalRuangan }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-secondary"><i class="fas fa-door-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kunjungan Hari Ini</span>
                                    <span class="info-box-number">{{ $kunjunganHariIni }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Aktivitas Hari Ini
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-tools"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Alat</span>
                                    <span class="info-box-number">{{ $penggunaanAlatHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Ruangan</span>
                                    <span class="info-box-number">{{ $penggunaanRuanganHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-flask"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Bahan</span>
                                    <span class="info-box-number">{{ $penggunaanBahanHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Kunjungan Bulan Ini</span>
                                    <span class="info-box-number">{{ $totalKunjunganBulanIni }}</span>
                                </div>
                            </div>
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
                        Pengajuan Terbaru
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuanTerbaru as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->user->name ?? '-' }}</td>
                                    <td>{{ Str::limit($pengajuan->judul_penelitian, 30) }}</td>
                                    <td>
                                        @if($pengajuan->status_validasi == 'Menunggu Laboran')
                                            <span class="badge badge-warning">Menunggu Laboran</span>
                                        @elseif($pengajuan->status_validasi == 'Menunggu Koordinator')
                                            <span class="badge badge-info">Menunggu Koordinator</span>
                                        @elseif($pengajuan->status_validasi == 'Diterima')
                                            <span class="badge badge-success">Diterima</span>
                                        @elseif($pengajuan->status_validasi == 'Ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @elseif($pengajuan->status_validasi == 'Selesai')
                                            <span class="badge badge-primary">Selesai</span>
                                        @endif
                                    </td>
                                    <td>{{ $pengajuan->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada pengajuan terbaru</td>
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
                        <i class="fas fa-bolt mr-1"></i>
                        Aksi Cepat
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.transaksi.peminjaman') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-file-signature"></i><br>
                                Validasi Pengajuan
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.transaksi.penggunaan') }}" class="btn btn-success btn-block">
                                <i class="fas fa-tools"></i><br>
                                Validasi Penggunaan
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.transaksi.pengembalian') }}" class="btn btn-info btn-block">
                                <i class="fas fa-undo"></i><br>
                                Validasi Pengembalian
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-door-open"></i><br>
                                Monitoring Kunjungan
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
                        <h5><i class="icon fas fa-info"></i> Dashboard Laboran</h5>
                        <p>Selamat datang di dashboard khusus laboran. Di sini Anda dapat:</p>
                        <ul>
                            <li>Memvalidasi pengajuan peminjaman mahasiswa</li>
                            <li>Memantau penggunaan alat dan ruangan</li>
                            <li>Mengelola inventaris laboratorium</li>
                            <li>Memantau kunjungan laboratorium</li>
                            <li>Melihat statistik aktivitas laboratorium</li>
                        </ul>
                        <p>Untuk mengelola laboratorium secara efektif, silakan kunjungi menu yang tersedia di sidebar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

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