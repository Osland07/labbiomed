<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Dashboard Laboran
    </x-slot>

    @include('components.alert')

    <!-- Content -->
    <div class="row">
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalAlat }}</h3>
                    <p>Total Alat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalBahan }}</h3>
                    <p>Total Bahan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-flask"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalRuangan }}</h3>
                    <p>Total Ruangan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-door-open"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <a href="{{ route('admin.alat.index') }}" class="text-decoration-none">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $alatPerluMaintenance }}</h3>
                        <p>Alat Perlu Maintenance</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wrench"></i>
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
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $bahanStokMenipis }}</h3>
                        <p>Bahan Stok Menipis</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
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
            <a href="{{ route('admin.transaksi.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $pengajuanMenungguLaboran }}</h3>
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
                        <i class="fas fa-chart-line mr-1"></i>
                        Aktivitas Hari Ini
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-door-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kunjungan</span>
                                    <span class="info-box-number">{{ $kunjunganHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-tools"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Alat</span>
                                    <span class="info-box-number">{{ $penggunaanAlatHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-building"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Ruangan</span>
                                    <span class="info-box-number">{{ $penggunaanRuanganHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-secondary"><i class="fas fa-flask"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Bahan</span>
                                    <span class="info-box-number">{{ $penggunaanBahanHariIni }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Laporan Kerusakan</span>
                                    <span class="info-box-number">{{ $laporanKerusakanHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-primary"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Divalidasi</span>
                                    <span class="info-box-number">{{ $pengajuanDivalidasiLaboran }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Pengajuan Menunggu Validasi
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Judul</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuanTerbaru as $pengajuan)
                                <tr>
                                    <td>{{ $pengajuan->user->name }}</td>
                                    <td>{{ Str::limit($pengajuan->judul_penelitian, 20) }}</td>
                                    <td>{{ $pengajuan->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada pengajuan menunggu validasi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        <i class="fas fa-tools mr-1"></i>
                        Alat Perlu Perhatian
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Nama Alat</th>
                                    <th>Kondisi</th>
                                    <th>Update Terakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alatPerluPerhatian as $alat)
                                <tr>
                                    <td>{{ $alat->nama }}</td>
                                    <td>
                                        @if($alat->kondisi == 'Rusak')
                                            <span class="badge badge-danger">Rusak</span>
                                        @elseif($alat->kondisi == 'Maintenance')
                                            <span class="badge badge-warning">Maintenance</span>
                                        @elseif($alat->kondisi == 'Kurang Baik')
                                            <span class="badge badge-secondary">Kurang Baik</span>
                                        @endif
                                    </td>
                                    <td>{{ $alat->updated_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada alat yang perlu perhatian</td>
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
                        <i class="fas fa-flask mr-1"></i>
                        Bahan Stok Menipis
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bahanStokMenipisList as $bahan)
                                <tr>
                                    <td>{{ $bahan->nama }}</td>
                                    <td>
                                        @if($bahan->stok < 5)
                                            <span class="badge badge-danger">{{ $bahan->stok }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $bahan->stok }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $bahan->satuan }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada bahan dengan stok menipis</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        <i class="fas fa-history mr-1"></i>
                        Aktivitas Terbaru
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Aktivitas</th>
                                    <th>User</th>
                                    <th>Detail</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aktivitasTerbaru as $aktivitas)
                                <tr>
                                    <td>
                                        @if($aktivitas->jenis_aktivitas == 'Kunjungan')
                                            <span class="badge badge-info">Kunjungan</span>
                                        @elseif($aktivitas->jenis_aktivitas == 'Penggunaan Alat')
                                            <span class="badge badge-success">Penggunaan Alat</span>
                                        @elseif($aktivitas->jenis_aktivitas == 'Laporan Kerusakan')
                                            <span class="badge badge-danger">Laporan Kerusakan</span>
                                        @endif
                                    </td>
                                    <td>{{ $aktivitas->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($aktivitas->jenis_aktivitas == 'Kunjungan')
                                            {{ $aktivitas->ruangan->nama ?? 'Ruangan' }}
                                        @elseif($aktivitas->jenis_aktivitas == 'Penggunaan Alat')
                                            {{ $aktivitas->alat->nama ?? 'Alat' }}
                                        @elseif($aktivitas->jenis_aktivitas == 'Laporan Kerusakan')
                                            {{ $aktivitas->alat->nama ?? 'Alat' }}
                                        @endif
                                    </td>
                                    <td>{{ $aktivitas->waktu->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada aktivitas terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                            <a href="{{ route('admin.transaksi.peminjaman') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-check-circle"></i><br>
                                Validasi Peminjaman
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.alat.index') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-tools"></i><br>
                                Kelola Alat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.bahan.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-flask"></i><br>
                                Kelola Bahan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.laporan.kerusakan') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-exclamation-triangle"></i><br>
                                Laporan Kerusakan
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
                            <li>Memantau kondisi semua alat laboratorium</li>
                            <li>Melihat stok bahan yang menipis</li>
                            <li>Memvalidasi pengajuan peminjaman</li>
                            <li>Melihat aktivitas laboratorium secara real-time</li>
                            <li>Memantau laporan kerusakan alat</li>
                            <li>Mengelola inventaris laboratorium</li>
                        </ul>
                        <p>Untuk mengelola alat, bahan, dan ruangan, silakan kunjungi menu yang tersedia di sidebar.</p>
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