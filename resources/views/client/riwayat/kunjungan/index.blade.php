<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Riwayat Kunjungan Saya
    </x-slot>

    @include('components.alert')

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Riwayat Kunjungan Saya</h1>
                <p class="text-muted">Lihat semua riwayat kunjungan Anda ke laboratorium</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="mr-3">
                    <span class="badge badge-primary">Total: {{ $kunjungans->total() }}</span>
                </div>
                <div class="mr-3">
                    <span class="badge badge-success">Hari Ini: {{ $kunjungans->where('waktu_masuk', '>=', \Carbon\Carbon::today())->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Kunjungan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Kunjungan Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->where('waktu_masuk', '>=', \Carbon\Carbon::now()->startOfMonth())->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Ruangan Favorit</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $kunjungans->groupBy('ruangan_id')->sortByDesc(function($group) { return $group->count(); })->first()->first()->ruangan->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-heart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Kunjungan</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Aksi:</div>
                        <a class="dropdown-item" href="#" onclick="window.print()">
                            <i class="fas fa-print fa-sm fa-fw mr-2 text-gray-400"></i>
                            Cetak Riwayat
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th style="width: 20%">Ruangan</th>
                                <th style="width: 25%">Keperluan</th>
                                <th style="width: 15%">Waktu Masuk</th>
                                <th style="width: 15%">Waktu Keluar</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 10%">Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kunjungans as $index => $k)
                            <tr>
                                <td class="text-center">{{ $index + 1 + ($kunjungans->currentPage() - 1) * $kunjungans->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                                                <i class="fas fa-building text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <strong class="text-primary">{{ $k->ruangan->name ?? '-' }}</strong>
                                            @if($k->ruangan)
                                                <br><small class="text-muted">{{ $k->ruangan->lokasi ?? 'Lokasi tidak tersedia' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 300px;">
                                        <strong>{{ Str::limit($k->tujuan, 60) }}</strong>
                                        @if(strlen($k->tujuan) > 60)
                                            <br><small class="text-muted">{{ $k->tujuan }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-sign-in-alt text-success mr-2"></i>
                                            <strong>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('H:i') }}</strong>
                                        </div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d/m/Y') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($k->waktu_keluar)
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-sign-out-alt text-danger mr-2"></i>
                                                <strong>{{ \Carbon\Carbon::parse($k->waktu_keluar)->format('H:i') }}</strong>
                                            </div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($k->waktu_keluar)->format('d/m/Y') }}</small>
                                        </div>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i>Masih di Lab
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($k->waktu_keluar)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>Selesai
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock mr-1"></i>Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($k->waktu_keluar)
                                        @php
                                            $duration = \Carbon\Carbon::parse($k->waktu_masuk)->diffInMinutes(\Carbon\Carbon::parse($k->waktu_keluar));
                                            $hours = floor($duration / 60);
                                            $minutes = $duration % 60;
                                        @endphp
                                        <span class="badge badge-info">
                                            {{ $hours }}j {{ $minutes }}m
                                        </span>
                                    @else
                                        @php
                                            $duration = \Carbon\Carbon::parse($k->waktu_masuk)->diffInMinutes(now());
                                            $hours = floor($duration / 60);
                                            $minutes = $duration % 60;
                                        @endphp
                                        <span class="badge badge-warning">
                                            {{ $hours }}j {{ $minutes }}m
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-calendar-times fa-2x text-gray-400"></i>
                                        </div>
                                        <h6 class="text-gray-500 mb-2">Belum ada riwayat kunjungan</h6>
                                        <p class="text-muted text-center mb-3">Anda belum pernah melakukan kunjungan ke laboratorium</p>
                                        <a href="{{ route('beranda') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus mr-1"></i>Mulai Kunjungan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $kunjungans->links() }}
                </div>
            </div>
        </div>

        <!-- Recent Activity Timeline -->
        @if($kunjungans->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($kunjungans->take(5) as $k)
                    <div class="timeline-item d-flex mb-3">
                        <div class="timeline-marker mr-3">
                            @if($k->waktu_keluar)
                                <div class="w-8 h-8 bg-success rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-warning rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xs"></i>
                                </div>
                            @endif
                        </div>
                        <div class="timeline-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="text-gray-800">{{ $k->ruangan->name ?? 'Ruangan' }}</strong>
                                    <p class="text-muted mb-1">{{ $k->tujuan }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d M Y, H:i') }}
                                    </small>
                                </div>
                                <div class="text-right">
                                    @if($k->waktu_keluar)
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Aktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .timeline-item {
            position: relative;
        }
        
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 15px;
            top: 32px;
            bottom: -16px;
            width: 2px;
            background-color: #e3e6f0;
        }
        
        .timeline-marker {
            flex-shrink: 0;
        }
    </style>
</x-admin-table>