<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link border-bottom text-center">
        <div class="brand-image">
            <img src="{{ asset('assets/logo.jpg') }}" class="img-circle elevation-2 img-fluid" width="35" alt="">
        </div>
        <span class="brand-text font-weight-bold text-white">{{ strtoupper(config('app.name')) }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <x-sidebar-link route="admin.dashboard" icon="tachometer-alt" label="Dashboard" />

                @php
                    $dataMasterRoutes = [
                        'admin.user.index',
                        'admin.role.index',
                        'admin.ruangan.index',
                        'admin.alat.index',
                        'admin.bahan.index',
                        'admin.category.index',
                    ];

                    $dataTransaksiRoutes = [
                        'admin.transaksi.peminjaman',
                        'admin.transaksi.penggunaan',
                        'admin.transaksi.pengembalian',
                    ];

                    $dataLaporanRoutes = [
                        'admin.laporan.peminjaman',
                        'admin.laporan.penggunaan',
                        'admin.laporan.kerusakan',
                    ];

                    $dataKunjunganRoutes = [
                        'admin.kunjungan.index',
                        'admin.kunjungan.generate-qr',
                    ];

                    $dataMonitoringRoutes = [
                        'admin.monitoring.index',
                        'admin.monitoring.detail',
                        'admin.monitoring.laporan',
                    ];

                    $dataPengajuanRoutes = ['client.pengajuan-peminjaman.index', 'client.pengajuan-peminjaman.upload'];
                    $dataRiwayatRoutes = ['client.riwayat-pengajuan', 'client.riwayat-penggunaan', 'client.riwayat-kunjungan'];

                    $isDataMasterActive = in_array(Route::currentRouteName(), $dataMasterRoutes);
                    $isDataTransaksiActive = in_array(Route::currentRouteName(), $dataTransaksiRoutes);
                    $isDataLaporanActive = in_array(Route::currentRouteName(), $dataLaporanRoutes);
                    $isDataKunjunganActive = in_array(Route::currentRouteName(), $dataKunjunganRoutes);
                    $isDataMonitoringActive = in_array(Route::currentRouteName(), $dataMonitoringRoutes);
                    $isDataPengajuanActive = in_array(Route::currentRouteName(), $dataPengajuanRoutes);
                    $isDataRiwayatActive = in_array(Route::currentRouteName(), $dataRiwayatRoutes);
                @endphp

                @if (Auth::user()->can('view-user') ||
                        Auth::user()->can('view-role') ||
                        Auth::user()->can('view-alat') ||
                        Auth::user()->can('view-bahan') ||
                        Auth::user()->can('view-ruangan') ||
                        Auth::user()->can('view-category'))
                    <li class="nav-item has-treeview {{ $isDataMasterActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataMasterActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                Data Master
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <x-sidebar-link route="admin.user.index" label="Data User" can="view-user" />
                            <x-sidebar-link route="admin.role.index" label="Data Role" can="view-role" />
                            <x-sidebar-link route="admin.ruangan.index" label="Data Ruangan" can="view-ruangan" />
                            <x-sidebar-link route="admin.alat.index" label="Data Alat" can="view-alat" />
                            <x-sidebar-link route="admin.bahan.index" label="Data Bahan" can="view-bahan" />
                            <x-sidebar-link route="admin.category.index" label="Data Kategori" can="view-category" />
                        </ul>
                    </li>
                @endif

                @can('penggunaan-ruangan-client')
                    <x-sidebar-link route="client.penggunaan-ruangan" icon="door-open" label="Penggunaan Ruangan" can="penggunaan-ruangan-client" />
                @endcan
                @can('penggunaan-bahan-client')
                    <x-sidebar-link route="client.penggunaan-bahan" icon="flask" label="Penggunaan Bahan" can="penggunaan-bahan-client" />
                @endcan

                @can('view-transaksi')
                    <li class="nav-item has-treeview {{ $isDataTransaksiActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataTransaksiActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>
                                Transaksi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('peminjaman-transaksi')
                                <li class="nav-item ps-4 {{ Request::routeIs('admin.transaksi.peminjaman') ? 'aktif' : '' }}">
                                    <a href="{{ route('admin.transaksi.peminjaman') }}" class="nav-link text-white">
                                        <p>
                                            Validasi Peminjaman
                                            @if ($jumlahValidasiPeminjaman > 0)
                                                <span class="badge badge-danger">{{ $jumlahValidasiPeminjaman }}</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('penggunaan-transaksi')
                                <li class="nav-item ps-4 {{ Request::routeIs('admin.transaksi.penggunaan') ? 'aktif' : '' }}">
                                    <a href="{{ route('admin.transaksi.penggunaan') }}" class="nav-link text-white">
                                        <p>
                                            Validasi Penggunaan
                                            @if ($jumlahValidasiPenggunaan > 0)
                                                <span class="badge badge-danger">{{ $jumlahValidasiPenggunaan }}</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('pengembalian-transaksi')
                                <li
                                    class="nav-item ps-4 {{ Request::routeIs('admin.transaksi.pengembalian') ? 'aktif' : '' }}">
                                    <a href="{{ route('admin.transaksi.pengembalian') }}" class="nav-link text-white">
                                        <p>
                                            Validasi Pengembalian
                                            @if ($jumlahValidasiPengembalian > 0)
                                                <span class="badge badge-danger">{{ $jumlahValidasiPengembalian }}</span>
                                            @endif
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @can('view-laporan')
                    <li class="nav-item has-treeview {{ $isDataLaporanActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataLaporanActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-signature"></i>
                            <p>
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <x-sidebar-link route="admin.laporan.peminjaman" label="Laporan Peminjaman"
                                can="peminjaman-laporan" />
                            <x-sidebar-link route="admin.laporan.penggunaan" label="Laporan Penggunaan"
                                can="penggunaan-laporan" />
                            <x-sidebar-link route="admin.laporan.kerusakan" label="Laporan Kerusakan"
                                can="kerusakan-laporan" />
                        </ul>
                    </li>
                @endcan

                @can('monitoring-mahasiswa')
                    <li class="nav-item has-treeview {{ $isDataMonitoringActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataMonitoringActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Monitoring
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <x-sidebar-link route="admin.monitoring.index" label="Aktivitas Mahasiswa" can="monitoring-mahasiswa" />
                            <x-sidebar-link route="admin.monitoring.laporan" label="Laporan Aktivitas" can="monitoring-mahasiswa" />
                        </ul>
                    </li>
                @endcan

                @if (Auth::user()->can('view-kunjungan'))
                    <li class="nav-item has-treeview {{ $isDataKunjunganActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataKunjunganActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Kunjungan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <x-sidebar-link route="admin.kunjungan.index" label="Data Kunjungan" can="view-kunjungan" />
                            <x-sidebar-link route="admin.kunjungan.generate-qr" label="Generate QR Code" can="view-kunjungan" />
                        </ul>
                    </li>
                @endif

                @can('check-client')
                    <x-sidebar-link route="client.check.index" icon="search" label="Cek Ketersediaan" can="check-client" />
                @endcan

                @can('pengajuan-peminjaman-client')
                    <li class="nav-item has-treeview {{ $isDataPengajuanActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataPengajuanActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-signature"></i>
                            <p>
                                Pengajuan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <x-sidebar-link route="client.pengajuan-peminjaman.index" label="Formulir Pengajuan"
                                can="pengajuan-peminjaman-client" />
                            <x-sidebar-link route="client.pengajuan-peminjaman.upload" label="Upload Surat"
                                can="pengajuan-peminjaman-client" />
                        </ul>
                    </li>
                @endcan

                @can('penggunaan-alat-client')
                    <x-sidebar-link route="client.penggunaan-alat" icon="tools" label="Penggunaan Alat"
                        can="penggunaan-alat-client" />
                @endcan

                @can('history-client')
                    <li class="nav-item has-treeview {{ $isDataRiwayatActive ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link text-white {{ $isDataRiwayatActive ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Riwayat
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <x-sidebar-link route="client.riwayat-pengajuan" label="Pengajuan" can="history-client" />
                            <x-sidebar-link route="client.riwayat-penggunaan" label="Penggunaan" can="history-client" />
                            <x-sidebar-link route="client.riwayat-kunjungan" label="Kunjungan" can="history-client" />
                        </ul>
                    </li>
                @endcan

                @can('jadwal-dashboard')
                    <x-sidebar-link route="jadwal" icon="calendar" label="Jadwal" can="jadwal-dashboard" />
                @endcan

                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>
                        @csrf
                    </form>
                    <a href="#" class="nav-link text-white"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>

        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
