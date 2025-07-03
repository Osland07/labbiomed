<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Dashboard Mahasiswa
    </x-slot>

    @include('components.alert')

    <!-- Content -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('client.riwayat-pengajuan') }}" class="text-decoration-none">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pengajuanPending }}</h3>
                        <p>Pengajuan Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
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
            <a href="{{ route('client.penggunaan-alat') }}" class="text-decoration-none">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $pengajuanDisetujui }}</h3>
                        <p>Pengajuan Disetujui</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="small-box-footer">
                        Gunakan Alat <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('client.pengajuan-peminjaman.index') }}" class="text-decoration-none">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $pengajuanSelesai }}</h3>
                        <p>Pengajuan Selesai</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="small-box-footer">
                        Ajukan Baru <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('client.riwayat-pengajuan') }}" class="text-decoration-none">
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
                        <i class="fas fa-chart-line mr-1"></i>
                        Aktivitas Hari Ini
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-door-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kunjungan</span>
                                    <span class="info-box-number">{{ $kunjunganHariIni }}</span>
                                </div>
                            </div>
                        </div>
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
                                <span class="info-box-icon bg-secondary"><i class="fas fa-flask"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Bahan</span>
                                    <span class="info-box-number">{{ $penggunaanBahanHariIni }}</span>
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
                        <i class="fas fa-chart-pie mr-1"></i>
                        Statistik Bulan Ini
                    </h3>
                </div>
                <div class="card-body">
                    <div class="info-box bg-light">
                        <span class="info-box-icon bg-info"><i class="fas fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kunjungan Bulan Ini</span>
                            <span class="info-box-number">{{ $totalKunjunganBulanIni }}</span>
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
                                    <th>Judul</th>
                                    <th>Dosen</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuanTerbaru as $pengajuan)
                                <tr>
                                    <td>{{ Str::limit($pengajuan->judul_penelitian, 30) }}</td>
                                    <td>{{ $pengajuan->dosen->name ?? '-' }}</td>
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
                                        @elseif($aktivitas->jenis_aktivitas == 'Penggunaan Ruangan')
                                            <span class="badge badge-warning">Penggunaan Ruangan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($aktivitas->jenis_aktivitas == 'Kunjungan')
                                            {{ $aktivitas->ruangan->nama ?? 'Ruangan' }}
                                        @elseif($aktivitas->jenis_aktivitas == 'Penggunaan Alat')
                                            {{ $aktivitas->alat->nama ?? 'Alat' }}
                                        @elseif($aktivitas->jenis_aktivitas == 'Penggunaan Ruangan')
                                            {{ $aktivitas->ruangan->nama ?? 'Ruangan' }}
                                        @endif
                                    </td>
                                    <td>{{ $aktivitas->waktu->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada aktivitas terbaru</td>
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
                            <a href="{{ route('client.pengajuan-peminjaman.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-file-signature"></i><br>
                                Ajukan Peminjaman
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.penggunaan-alat') }}" class="btn btn-success btn-block">
                                <i class="fas fa-tools"></i><br>
                                Gunakan Alat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.penggunaan-ruangan') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-door-open"></i><br>
                                Gunakan Ruangan
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('client.penggunaan-bahan') }}" class="btn btn-info btn-block">
                                <i class="fas fa-flask"></i><br>
                                Gunakan Bahan
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
                        <h5><i class="icon fas fa-info"></i> Dashboard Mahasiswa</h5>
                        <p>Selamat datang di dashboard khusus mahasiswa. Di sini Anda dapat:</p>
                        <ul>
                            <li>Melihat status pengajuan peminjaman alat/ruangan</li>
                            <li>Memantau aktivitas laboratorium Anda</li>
                            <li>Melihat riwayat kunjungan dan penggunaan</li>
                            <li>Mengajukan peminjaman baru</li>
                            <li>Menggunakan alat, ruangan, dan bahan</li>
                        </ul>
                        <p>Untuk mengajukan peminjaman atau menggunakan fasilitas laboratorium, silakan kunjungi menu yang tersedia di sidebar.</p>
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