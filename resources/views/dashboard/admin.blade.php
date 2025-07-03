<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Dashboard Admin
    </x-slot>

    @include('components.alert')

    <!-- Content -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.user.index') }}" class="text-decoration-none">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $users }}</h3>
                        <p>Total Pengguna</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="small-box-footer">
                        Kelola Pengguna <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.role.index') }}" class="text-decoration-none">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $roles }}</h3>
                        <p>Total Role</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <div class="small-box-footer">
                        Kelola Role <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.laporan.peminjaman') }}" class="text-decoration-none">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pengajuan }}</h3>
                        <p>Total Pengajuan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="small-box-footer">
                        Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <a href="{{ route('admin.laporan.penggunaan') }}" class="text-decoration-none">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $penggunaan }}</h3>
                        <p>Penggunaan Baik</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="small-box-footer">
                        Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
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
                        Statistik Sistem
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Penggunaan Baik</span>
                                    <span class="info-box-number">{{ $penggunaan }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Laporan Kerusakan</span>
                                    <span class="info-box-number">{{ $kerusakan }}</span>
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
                        <i class="fas fa-cogs mr-1"></i>
                        Pengaturan Sistem
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Manajemen User</span>
                                    <span class="info-box-number">{{ $users }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-user-tag"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Manajemen Role</span>
                                    <span class="info-box-number">{{ $roles }}</span>
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
                            <a href="{{ route('admin.user.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-users"></i><br>
                                Kelola User
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.role.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-user-tag"></i><br>
                                Kelola Role
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.laporan.peminjaman') }}" class="btn btn-success btn-block">
                                <i class="fas fa-file-signature"></i><br>
                                Laporan Peminjaman
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.laporan.kerusakan') }}" class="btn btn-danger btn-block">
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
                        <h5><i class="icon fas fa-info"></i> Dashboard Admin</h5>
                        <p>Selamat datang di dashboard khusus admin. Di sini Anda dapat:</p>
                        <ul>
                            <li>Mengelola semua pengguna sistem</li>
                            <li>Mengatur role dan permission</li>
                            <li>Melihat laporan peminjaman dan penggunaan</li>
                            <li>Memantau laporan kerusakan</li>
                            <li>Mengakses semua fitur administrasi</li>
                        </ul>
                        <p>Untuk mengelola sistem secara menyeluruh, silakan kunjungi menu yang tersedia di sidebar.</p>
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