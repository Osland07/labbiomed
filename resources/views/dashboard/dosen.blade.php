<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Dashboard Dosen
    </x-slot>

    @include('components.alert')

    <!-- Content -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.monitoring.index') }}" class="text-decoration-none">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $mahasiswaBimbingan }}</h3>
                        <p>Mahasiswa Bimbingan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        Lihat Monitoring <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pengajuanPending }}</h3>
                    <p>Pengajuan Pending</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="small-box-footer">
                    Menunggu Validasi <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $pengajuanDisetujui }}</h3>
                    <p>Pengajuan Disetujui</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="small-box-footer">
                    Sedang Berjalan <i class="fas fa-play-circle"></i>
                </div>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $pengajuanSelesai }}</h3>
                    <p>Pengajuan Selesai</p>
                </div>
                <div class="icon">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="small-box-footer">
                    Telah Selesai <i class="fas fa-check-circle"></i>
                </div>
            </div>
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
                                    <span class="info-box-number">{{ $aktivitasHariIni }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-flask"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Bahan</span>
                                    <span class="info-box-number">{{ $penggunaanBahan }}</span>
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
                        <i class="fas fa-list mr-1"></i>
                        Pengajuan Terbaru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Lihat Semua
                        </a>
                    </div>
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
                                <tr style="cursor: pointer;" onclick="window.location.href='{{ route('admin.monitoring.detail', $pengajuan->user->id) }}'">
                                    <td>{{ $pengajuan->user->name }}</td>
                                    <td>{{ Str::limit($pengajuan->judul_penelitian, 30) }}</td>
                                    <td>
                                        @if($pengajuan->status_validasi == 'Menunggu Laboran')
                                            <span class="badge badge-warning">Menunggu Laboran</span>
                                        @elseif($pengajuan->status_validasi == 'Menunggu Koordinator')
                                            <span class="badge badge-info">Menunggu Koordinator</span>
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
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.monitoring.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-users"></i><br>
                                Monitoring Mahasiswa
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.monitoring.laporan') }}" class="btn btn-success btn-block">
                                <i class="fas fa-chart-line"></i><br>
                                Laporan Aktivitas
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('jadwal') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-calendar"></i><br>
                                Jadwal Laboratorium
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
                        <h5><i class="icon fas fa-info"></i> Dashboard Dosen</h5>
                        <p>Selamat datang di dashboard khusus dosen. Di sini Anda dapat:</p>
                        <ul>
                            <li>Memantau pengajuan peminjaman dari mahasiswa bimbingan</li>
                            <li>Melihat aktivitas laboratorium mahasiswa bimbingan</li>
                            <li>Memantau penggunaan bahan oleh mahasiswa bimbingan</li>
                            <li>Melihat status pengajuan yang sedang dalam proses</li>
                        </ul>
                        <p>Untuk melihat detail monitoring mahasiswa bimbingan, silakan kunjungi menu <strong>Monitoring</strong>.</p>
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