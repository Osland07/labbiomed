<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Riwayat Kunjungan
    </x-slot>


    @include('components.alert')



        <!-- Enhanced Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Kunjungan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->total() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Kunjungan Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->where('waktu_masuk', '>=', \Carbon\Carbon::today())->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Kunjungan Minggu Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kunjungans->where('waktu_masuk', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
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
        </div>

        <!-- Search and Filter Card -->
        <div class="card shadow mb-4" id="searchFilterCard">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-search mr-2"></i>Pencarian & Filter
                </h6>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapseClient">
                    <i class="fas fa-chevron-down"></i>
                    <span id="toggleText">Tampilkan</span>
                </button>
            </div>
            <div class="collapse" id="filterCollapseClient">
                <div class="card-body" id="searchFilterBody">
                    <form method="GET" action="{{ route('client.riwayat-kunjungan') }}" class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search" class="form-label">Cari</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ $search }}" placeholder="Cari berdasarkan tujuan atau ruangan...">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="perPage" class="form-label">Data per Halaman</label>
                            <select class="form-control" id="perPage" name="perPage">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100" title="Cari">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <a href="{{ route('client.riwayat-kunjungan') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-refresh mr-1"></i>Reset Semua Filter
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            @if($search || request('status') || request('date_from') || request('date_to'))
                                <span class="badge badge-info">
                                    <i class="fas fa-filter mr-1"></i>
                                    Filter Aktif: 
                                    @if($search) Pencarian, @endif
                                    @if(request('status')) Status, @endif
                                    @if(request('date_from') || request('date_to')) Tanggal, @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>Riwayat Kunjungan ({{ $kunjungans->total() }} data)
                </h6>
                @if($search || request('status') || request('date_from') || request('date_to'))
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-filter mr-1"></i>
                            Filter Aktif: 
                            @if($search) 
                                <span class="badge badge-primary">Pencarian: "{{ $search }}"</span>
                            @endif
                            @if(request('status')) 
                                <span class="badge badge-info">Status: {{ ucfirst(request('status')) }}</span>
                            @endif
                            @if(request('date_from') || request('date_to'))
                                <span class="badge badge-success">
                                    Tanggal: 
                                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : 'Awal' }} 
                                    - 
                                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : 'Akhir' }}
                                </span>
                            @endif
                            <a href="{{ route('client.riwayat-kunjungan') }}" class="btn btn-sm btn-outline-secondary ml-2">
                                <i class="fas fa-times mr-1"></i>Hapus Semua
                            </a>
                        </small>
                    </div>
                @endif
            </div>
            <div class="card-body">
                @if($kunjungans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-center" style="width: 8%">
                                        <i class="fas fa-hashtag mr-1"></i>No
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        <i class="fas fa-building mr-1"></i>Ruangan
                                    </th>
                                    <th class="text-center" style="width: 25%">
                                        <i class="fas fa-bullseye mr-1"></i>Tujuan
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        <i class="fas fa-sign-in-alt mr-1"></i>Waktu Masuk
                                    </th>
                                    <th class="text-center" style="width: 15%">
                                        <i class="fas fa-sign-out-alt mr-1"></i>Waktu Keluar
                                    </th>
                                    <th class="text-center" style="width: 12%">
                                        <i class="fas fa-clock mr-1"></i>Durasi
                                    </th>
                                    <th class="text-center" style="width: 10%">
                                        <i class="fas fa-info-circle mr-1"></i>Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kunjungans as $index => $kunjungan)
                                    <tr>
                                        <td class="text-center">{{ $kunjungans->firstItem() + $index }}</td>
                                        <td class="text-center">
                                            <span class="font-weight-bold text-primary">{{ $kunjungan->ruangan->name }}</span>
                                        </td>
                                        <td class="text-center">{{ $kunjungan->tujuan }}</td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('d/m/Y') }}</small>
                                                <strong>{{ \Carbon\Carbon::parse($kunjungan->waktu_masuk)->format('H:i') }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($kunjungan->waktu_keluar)
                                                <div class="d-flex flex-column align-items-center">
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($kunjungan->waktu_keluar)->format('d/m/Y') }}</small>
                                                    <strong>{{ \Carbon\Carbon::parse($kunjungan->waktu_keluar)->format('H:i') }}</strong>
                                                </div>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-clock mr-1"></i>Masih di Lab
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($kunjungan->waktu_keluar)
                                                @php
                                                    $waktuMasuk = \Carbon\Carbon::parse($kunjungan->waktu_masuk);
                                                    $waktuKeluar = \Carbon\Carbon::parse($kunjungan->waktu_keluar);
                                                    $durasi = $waktuMasuk->diffInMinutes($waktuKeluar);
                                                    $hours = floor($durasi / 60);
                                                    $minutes = $durasi % 60;
                                                @endphp
                                                <span class="badge badge-info">
                                                    {{ $hours }}j {{ $minutes }}m
                                                </span>
                                            @else
                                                @php
                                                    $waktuMasuk = \Carbon\Carbon::parse($kunjungan->waktu_masuk);
                                                    $durasi = $waktuMasuk->diffInMinutes(now());
                                                    $hours = floor($durasi / 60);
                                                    $minutes = $durasi % 60;
                                                @endphp
                                                <span class="badge badge-warning">
                                                    {{ $hours }}j {{ $minutes }}m
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($kunjungan->waktu_keluar)
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning">Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Menampilkan {{ $kunjungans->firstItem() }} sampai {{ $kunjungans->lastItem() }} dari {{ $kunjungans->total() }} data
                        </div>
                        <div>
                            {{ $kunjungans->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">Tidak ada data kunjungan</h5>
                        <p class="text-gray-400">Anda belum memiliki riwayat kunjungan ke laboratorium.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Table Header Styling */
        #dataTable thead th {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 15px 8px;
            position: relative;
            transition: all 0.3s ease;
        }

        #dataTable thead th:hover {
            background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        #dataTable thead th i {
            font-size: 0.9rem;
            opacity: 0.9;
            transition: all 0.3s ease;
        }

        #dataTable thead th:hover i {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Table Body Styling */
        #dataTable tbody tr {
            transition: all 0.2s ease;
        }

        #dataTable tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Smooth transition for search filter */
        #searchFilterBody {
            transition: all 0.3s ease-in-out;
        }
        
        /* Button hover effects */
        #toggleSearchFilter {
            transition: all 0.2s ease-in-out;
        }
        
        #toggleSearchFilter:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Icon rotation animation */
        #toggleIcon {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Card header hover effect */
        #searchFilterCard .card-header:hover {
            background-color: #f8f9fc;
            transition: background-color 0.2s ease-in-out;
        }

        /* Quick filter button styles */
        .quick-filter {
            transition: all 0.2s ease-in-out;
            border-radius: 20px !important;
            margin-right: 5px;
        }

        .quick-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .quick-filter.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-group-sm .btn {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .quick-filter {
                margin-bottom: 5px;
            }
            
            #searchFilterCard .card-header {
                flex-direction: column;
                gap: 10px;
            }
            
            #toggleSearchFilter {
                align-self: flex-end;
            }
        }

        /* Loading animation */
        .fa-spin {
            animation: fa-spin 1s infinite linear;
        }

        @keyframes fa-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Notification styles */
        .alert {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Form validation styles */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        /* Search input with clear button */
        #search {
            padding-right: 35px;
        }

        #clearSearch {
            border: none;
            background: transparent;
            color: #6c757d;
            padding: 0.375rem 0.5rem;
        }

        #clearSearch:hover {
            color: #dc3545;
            background: transparent;
        }

        /* Print styles */
        @media print {
            #searchFilterCard, #toggleSearchFilter, #exportData, #printData, .pagination {
                display: none !important;
            }
            
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            
            .table {
                font-size: 12px;
            }
        }
    </style>
    <script>
    // Sisakan hanya script-script lain yang memang dibutuhkan (misal: quick filter, validasi tanggal, dsb)
    // Hapus semua kode JS toggle filter custom di sini
    // Contoh: kode quick filter, validasi tanggal, dsb tetap dipertahankan
    $(document).ready(function() {
        // Quick filter, validasi tanggal, dsb
        // ...
    });
    </script>
    @endpush

</x-admin-table> 