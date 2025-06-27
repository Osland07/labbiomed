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
                <a href="{{ route('kunjungan.dashboard') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-dashboard mr-1"></i>Dashboard Kunjungan
                </a>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Kunjungan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->total() }}</div>
                                <div class="text-xs text-muted">Semua waktu</div>
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
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
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
                                    Kunjungan Minggu Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->where('waktu_masuk', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</div>
                                <div class="text-xs text-muted">7 hari terakhir</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>Riwayat Kunjungan ({{ $kunjungans->total() }} data)
                </h6>
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
                                <th style="width: 20%">Tujuan</th>
                                <th style="width: 15%">Waktu Masuk</th>
                                <th style="width: 15%">Waktu Keluar</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kunjungans as $index => $k)
                            <tr>
                                <td class="text-center">{{ $index + 1 + ($kunjungans->currentPage() - 1) * $kunjungans->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas fa-building text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $k->ruangan->name ?? '-' }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 250px;">
                                        {{ Str::limit($k->tujuan, 60) }}
                                        @if(strlen($k->tujuan) > 60)
                                            <button type="button" class="btn btn-sm btn-link p-0 ml-1" data-toggle="tooltip" data-placement="top" title="{{ $k->tujuan }}">
                                                <i class="fas fa-info-circle text-info"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d/m/Y') }}</small>
                                        <strong>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('H:i') }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if($k->waktu_keluar)
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($k->waktu_keluar)->format('d/m/Y') }}</small>
                                            <strong>{{ \Carbon\Carbon::parse($k->waktu_keluar)->format('H:i') }}</strong>
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
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history mr-2"></i>Aktivitas Kunjungan Terbaru
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($kunjungans->take(5) as $k)
                    <div class="timeline-item">
                        <div class="timeline-marker {{ $k->waktu_keluar ? 'bg-success' : 'bg-warning' }}">
                            <i class="fas {{ $k->waktu_keluar ? 'fa-check' : 'fa-clock' }} text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1">{{ $k->ruangan->name ?? 'Ruangan' }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($k->waktu_masuk)->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit($k->tujuan, 100) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-sign-in-alt mr-1"></i>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d/m/Y H:i') }}
                                @if($k->waktu_keluar)
                                    <i class="fas fa-sign-out-alt ml-2 mr-1"></i>{{ \Carbon\Carbon::parse($k->waktu_keluar)->format('d/m/Y H:i') }}
                                @endif
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-marker {
            position: absolute;
            left: -35px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .timeline-content {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4e73df;
        }
        .gap-2 {
            gap: 0.5rem;
        }
    </style>

    <script>
        // Initialize tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</x-admin-table>