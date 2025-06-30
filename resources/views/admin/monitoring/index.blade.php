@extends('layouts.admin.app')

@section('title', 'Monitoring Aktivitas Mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Monitoring Aktivitas Mahasiswa</h1>
        <p class="text-gray-600">
            @if(auth()->user()->hasRole('Dosen'))
                Pantau aktivitas mahasiswa bimbingan Anda
            @else
                Pantau aktivitas semua mahasiswa
            @endif
        </p>
    </div>

    <x-admin-table>

        <!-- Title -->
        <x-slot name="title">
            Monitoring Aktivitas Mahasiswa
        </x-slot>

        <!-- Search & Pagination -->
        <x-slot name="search">
            <form method="GET" action="{{ route('admin.monitoring.index') }}" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari mahasiswa..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="perPage" class="form-control" onchange="this.form.submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per halaman</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 per halaman</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per halaman</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.monitoring.laporan') }}" class="btn btn-success">
                            <i class="fas fa-chart-bar mr-2"></i>Laporan Aktivitas
                        </a>
                    </div>
                </div>
            </form>
        </x-slot>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $mahasiswaBimbingan->total() }}</h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ \App\Models\Kunjungan::whereIn('user_id', $mahasiswaBimbingan->pluck('id'))->whereDate('waktu_masuk', today())->count() }}</h3>
                        <p>Aktif Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ \App\Models\Laporan::whereIn('user_id', $mahasiswaBimbingan->pluck('id'))->where('alat_id', '!=', null)->whereDate('waktu_mulai', today())->count() }}</h3>
                        <p>Penggunaan Alat</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ \App\Models\LaporanPeminjaman::whereIn('user_id', $mahasiswaBimbingan->pluck('id'))->where('status_validasi', 'Diterima')->count() }}</h3>
                        <p>Peminjaman Aktif</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <table id="monitoring-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('No') }}</th>
                    <th>{{ __('Mahasiswa') }}</th>
                    <th>{{ __('NIM') }}</th>
                    <th>{{ __('Program Studi') }}</th>
                    <th>{{ __('Aktivitas Hari Ini') }}</th>
                    <th>{{ __('Total Kunjungan') }}</th>
                    <th class="text-center">{{ __('Aksi') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mahasiswaBimbingan as $mahasiswa)
                    <tr>
                        <td>{{ $mahasiswaBimbingan->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img class="img-circle mr-3" 
                                     src="{{ asset('assets/profile/' . ($mahasiswa->profile_photo_path ?? 'default.png')) }}" 
                                     alt="{{ $mahasiswa->name }}" width="40" height="40">
                                <div>
                                    <div class="font-weight-bold">{{ $mahasiswa->name }}</div>
                                    <small class="text-muted">{{ $mahasiswa->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $mahasiswa->nim ?? '-' }}</td>
                        <td>{{ $mahasiswa->prodi ?? '-' }}</td>
                        <td>
                            @php
                                $kunjunganHariIni = \App\Models\Kunjungan::where('user_id', $mahasiswa->id)->whereDate('waktu_masuk', today())->count();
                                $penggunaanHariIni = \App\Models\Laporan::where('user_id', $mahasiswa->id)->where('alat_id', '!=', null)->whereDate('waktu_mulai', today())->count();
                            @endphp
                            <div class="d-flex flex-wrap gap-1">
                                @if($kunjunganHariIni > 0)
                                    <span class="badge badge-success">
                                        <i class="fas fa-calendar-check mr-1"></i>{{ $kunjunganHariIni }} Kunjungan
                                    </span>
                                @endif
                                @if($penggunaanHariIni > 0)
                                    <span class="badge badge-info">
                                        <i class="fas fa-tools mr-1"></i>{{ $penggunaanHariIni }} Alat
                                    </span>
                                @endif
                                @if($kunjunganHariIni == 0 && $penggunaanHariIni == 0)
                                    <span class="text-muted">Tidak ada aktivitas</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-primary">
                                {{ \App\Models\Kunjungan::where('user_id', $mahasiswa->id)->count() }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.monitoring.detail', $mahasiswa->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">
                                    @if(auth()->user()->hasRole('Dosen'))
                                        Belum ada mahasiswa yang Anda bimbing atau belum ada pengajuan yang disetujui.
                                    @else
                                        Belum ada data mahasiswa yang tersedia.
                                    @endif
                                </h5>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $mahasiswaBimbingan->appends(['perPage' => $perPage, 'search' => $search])->links() }}

        <x-slot name="script">
            <script>
                $(document).ready(function() {
                    $('#monitoring-table').DataTable({
                        "paging": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                    });
                });
            </script>
        </x-slot>

    </x-admin-table>
</div>
@endsection 