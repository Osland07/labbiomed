<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Daftar Kunjungan Laboratorium
    </x-slot>

    @include('components.alert')
    
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Daftar Kunjungan Laboratorium</h1>
                <p class="text-muted">Kelola dan pantau semua kunjungan ke laboratorium</p>
            </div>
            <div class="d-flex align-items-center">
                <div class="mr-3">
                    <span class="badge badge-success">Total: {{ $stats['total'] }}</span>
                </div>
                <div class="mr-3">
                    <span class="badge badge-info">Hari Ini: {{ $stats['today'] }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="generateDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-link mr-1"></i>Generate Alamat
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="generateDropdown" style="max-height: 400px; overflow-y: auto;">
                        <h6 class="dropdown-header">Alamat Check-in Ruangan</h6>
                        @foreach($ruangans as $ruangan)
                            <a class="dropdown-item" href="javascript:void(0)" onclick="showUrlOverlay('{{ route('kunjungan.checkin', $ruangan->id) }}', 'Check-in {{ $ruangan->name }}')">
                                <i class="fas fa-sign-in-alt text-success mr-2"></i>Check-in {{ $ruangan->name }}
                            </a>
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Alamat Check-out Ruangan</h6>
                        @foreach($ruangans as $ruangan)
                            <a class="dropdown-item" href="javascript:void(0)" onclick="showUrlOverlay('{{ route('kunjungan.checkout', $ruangan->id) }}', 'Check-out {{ $ruangan->name }}')">
                                <i class="fas fa-sign-out-alt text-danger mr-2"></i>Check-out {{ $ruangan->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Kunjungan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total']) }}</div>
                                <div class="text-xs text-muted">Semua waktu</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today'] }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::today()->format('d M Y') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                    Sedang di Lab</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                                <div class="text-xs text-muted">Aktif sekarang</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['this_week'] }}</div>
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

        <!-- Additional Stats Row -->
        <div class="row mb-4">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Kunjungan Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['this_month'] }}</div>
                                <div class="text-xs text-muted">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-dark shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                    Ruangan Terpopuler</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ Str::limit($popularRoomName, 20) }}
                                </div>
                                <div class="text-xs text-muted">Paling banyak dikunjungi</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-star fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter mr-2"></i>Filter & Pencarian
                </h6>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="filterCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.kunjungan.index') }}" class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label"><i class="fas fa-search mr-1"></i>Pencarian</label>
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, NIM/NIP, tujuan..." value="{{ $request->search ?? '' }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label"><i class="fas fa-info-circle mr-1"></i>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="active" {{ $request->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ $request->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label"><i class="fas fa-building mr-1"></i>Ruangan</label>
                            <select name="ruangan_id" class="form-control">
                                <option value="">Semua Ruangan</option>
                                @foreach($ruangans as $ruangan)
                                    <option value="{{ $ruangan->id }}" {{ $request->ruangan_id == $ruangan->id ? 'selected' : '' }}>
                                        {{ $ruangan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label"><i class="fas fa-calendar mr-1"></i>Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $request->date_from ?? '' }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label"><i class="fas fa-calendar mr-1"></i>Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $request->date_to ?? '' }}">
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    @if($request->search || $request->status || $request->ruangan_id || $request->date_from || $request->date_to)
                        <div class="mt-3">
                            <a href="{{ route('admin.kunjungan.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times mr-1"></i>Hapus Filter
                            </a>
                        </div>
                    @endif
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
                            Cetak Laporan
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.kunjungan.export') }}?{{ http_build_query($request->all()) }}">
                            <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                            Export Excel
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
                                <th style="width: 15%">Ruangan</th>
                                <th style="width: 15%">Nama</th>
                                <th style="width: 12%">NIM/NIP</th>
                                <th style="width: 12%">Instansi</th>
                                <th style="width: 15%">Tujuan</th>
                                <th style="width: 12%">Waktu Masuk</th>
                                <th style="width: 12%">Waktu Keluar</th>
                                <th style="width: 12%">Status</th>
                                <th style="width: 10%">Durasi</th>
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
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <i class="fas fa-user text-info"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $k->nama }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($k->nim_nip)
                                        <span class="badge badge-secondary">{{ $k->nim_nip }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($k->instansi)
                                        <span class="text-muted">{{ $k->instansi }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-wrap" style="max-width: 200px;">
                                        {{ Str::limit($k->tujuan, 50) }}
                                        @if(strlen($k->tujuan) > 50)
                                            <button type="button" class="btn btn-sm btn-link p-0 ml-1" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $k->tujuan }}">
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
                                <td colspan="10" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox fa-3x text-gray-300"></i>
                                        </div>
                                        <h6 class="text-gray-500 mb-2">Belum ada data kunjungan</h6>
                                        <p class="text-muted text-center mb-3">Data kunjungan akan muncul di sini setelah ada pengunjung yang melakukan check-in</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $kunjungans->appends($request->all())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay untuk menampilkan URL -->
    <div id="urlOverlay" class="url-overlay" style="display: none;">
        <div class="url-overlay-content">
            <div class="url-overlay-header">
                <h5><i class="fas fa-link mr-2"></i><span id="overlayTitle">Alamat Check-in/Check-out</span></h5>
                <button type="button" class="close-overlay" onclick="hideUrlOverlay()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="url-overlay-body">
                <div class="form-group">
                    <label>URL:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="overlayUrlDisplay" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="copyUrlFromOverlay()">
                                <i class="fas fa-copy mr-1"></i>Copy
                            </button>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    URL ini dapat dibagikan kepada pengunjung untuk akses langsung ke halaman check-in/check-out.
                </div>
            </div>
            <div class="url-overlay-footer">
                <button type="button" class="btn btn-secondary" onclick="hideUrlOverlay()">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="openUrlFromOverlay()">
                    <i class="fas fa-external-link-alt mr-1"></i>Buka URL
                </button>
            </div>
        </div>
    </div>

    <style>
        .url-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .url-overlay-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .url-overlay-header {
            padding: 20px 20px 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
        }
        
        .url-overlay-header h5 {
            margin: 0;
            color: #333;
        }
        
        .close-overlay {
            background: none;
            border: none;
            font-size: 20px;
            color: #666;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-overlay:hover {
            color: #333;
        }
        
        .url-overlay-body {
            padding: 20px;
        }
        
        .url-overlay-footer {
            padding: 0 20px 20px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>

    <script>
        // Initialize tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        // Function to copy URL to clipboard
        function copyUrlToClipboard(url) {
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = url;
            document.body.appendChild(tempInput);
            
            // Select and copy the text
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            
            // Remove the temporary input
            document.body.removeChild(tempInput);
        }

        // Function to show URL overlay
        function showUrlOverlay(url, title) {
            document.getElementById('overlayTitle').innerText = title;
            document.getElementById('overlayUrlDisplay').value = url;
            document.getElementById('urlOverlay').style.display = 'flex';
        }

        // Function to hide URL overlay
        function hideUrlOverlay() {
            document.getElementById('urlOverlay').style.display = 'none';
        }

        // Function to copy URL from overlay
        function copyUrlFromOverlay() {
            const url = document.getElementById('overlayUrlDisplay').value;
            copyUrlToClipboard(url);
            showNotification('URL berhasil disalin ke clipboard!', 'success');
            hideUrlOverlay();
        }

        // Function to open URL from overlay
        function openUrlFromOverlay() {
            const url = document.getElementById('overlayUrlDisplay').value;
            window.open(url, '_blank');
            hideUrlOverlay();
        }

        // Function to show notifications
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} mr-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 3000);
        }

        // Close overlay when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('urlOverlay').addEventListener('click', function(e) {
                if (e.target === this) {
                    hideUrlOverlay();
                }
            });
        });
    </script>
</x-admin-table>