<x-admin-layout>
    <x-slot name="title">Cek Ketersediaan</x-slot>

    <div class="mb-5">
        {{-- Filter Form --}}
        <form method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3 mb-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                </div>
                <div class="col-md-1">
                    <select name="perPage" class="form-select">
                        <option value="12" {{ request('perPage') == 12 ? 'selected' : '' }}>12</option>
                        <option value="60" {{ request('perPage') == 60 ? 'selected' : '' }}>60</option>
                        <option value="120" {{ request('perPage') == 120 ? 'selected' : '' }}>120</option>
                    </select>
                </div>

                {{-- Dropdown pilih jenis data --}}
                <div class="col-md-2 mb-4">
                    <select name="type" class="form-select">
                        <option value="alat" {{ request('type', 'alat') == 'alat' ? 'selected' : '' }}>Alat</option>
                        @if (!auth()->user()->hasRole('Mahasiswa'))
                            <option value="bahan" {{ request('type') == 'bahan' ? 'selected' : '' }}>Bahan</option>
                        @endif
                        <option value="ruangan" {{ request('type') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">Terapkan</button>
                </div>
            </div>
        </form>

        @if (request('type', 'alat') === 'alat')
            {{-- Alat Section --}}
            <h4 class="mt-4 mb-3">Alat</h4>
            <div class="row">
                @foreach ($alats as $groupName => $items)
                    <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-4">
                        <div class="card h-100">
                            @if ($items->first()->img)
                                <img src="{{ asset('storage/' . $items->first()->img) }}" class="card-img-top"
                                    alt="{{ $groupName }}"
                                    style="height: 160px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 4px solid #fff;">
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" class="card-img-top" alt="Default"
                                    style="height: 160px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 4px solid #fff;">
                            @endif
                            <div class="card-body py-2">
                                <h5 class="card-title fw-bold text-primary">{{ Str::limit($groupName, 25, '...') }}</h5>
                                <p class="card-text m-0 p-0 text-muted">{{ $items->first()->ruangan->name }}</p>
                                <p class="card-text m-0 p-0">Total: {{ $items->count() }}</p>

                                {{-- Tombol untuk membuka modal --}}
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalDetail-{{ \Str::slug($groupName) }}">Lihat</a>

                                {{-- Modal --}}
                                <div class="modal fade" id="modalDetail-{{ \Str::slug($groupName) }}" tabindex="-1"
                                    role="dialog" aria-labelledby="modalLabel-{{ \Str::slug($groupName) }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalLabel-{{ \Str::slug($groupName) }}">
                                                    Detail
                                                    {{ $groupName }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                @php
                                                    $total = $items->count();
                                                    $jumlah_baik = $items->where('condition', 'Baik')->count();
                                                    $jumlah_rusak = $items->where('condition', 'Rusak')->count();
                                                    $jumlah_tersedia = $items->where('status', 'Tersedia')->count();
                                                @endphp

                                                <!-- Ringkasan Jumlah -->
                                                <div class="mb-3">
                                                    <span class="fw-bold">Ringkasan:</span>
                                                    <p class="m-0 p-0">Total Alat: {{ $total }}
                                                    </p>
                                                    <p class="m-0 p-0">Jumlah Baik: <span
                                                            class="text-success">{{ $jumlah_baik }}</span>
                                                    </p>
                                                    <p class="m-0 p-0">Jumlah Rusak: <span
                                                            class="text-danger">{{ $jumlah_rusak }}</span>
                                                    </p>
                                                    <p class="m-0 p-0">Jumlah Tersedia: <span
                                                            class="text-primary">{{ $jumlah_tersedia }}</span>
                                                    </p>
                                                </div>

                                                <!-- Tabel Detail -->
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Alat</th>
                                                            <th>Kondisi</th>
                                                            <th>Status</th>
                                                            <th>Lokasi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $alat)
                                                            <tr>
                                                                <td>{{ $alat->name }}</td>
                                                                <td>
                                                                    @if ($alat->condition == 'Baik')
                                                                        <span
                                                                            class="badge bg-success">{{ $alat->condition }}
                                                                        </span>
                                                                    @elseif ($alat->condition == 'Rusak')
                                                                        <span
                                                                            class="badge bg-danger">{{ $alat->condition }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-warning">{{ $alat->condition }}
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($alat->status == 'Tersedia')
                                                                        <span
                                                                            class="badge bg-primary">{{ $alat->status }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-warning">{{ $alat->status }}
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $alat->ruangan->name ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $alats->withQueryString()->links() }}
            </div>
        @endif

        @if (!auth()->user()->hasRole('Mahasiswa'))
            @if (request('type') === 'bahan')
                {{-- Bahan Section --}}
                <h4 class="mt-5 mb-3">Bahan</h4>
                <div class="row">
                    @forelse ($bahans as $bahan)
                        <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-4">
                            <div class="card h-100">
                                @if ($bahan->img)
                                    <img src="{{ asset('storage/' . $bahan->img) }}" class="card-img-top"
                                        alt="{{ $bahan->name }}">
                                @else
                                    <img src="{{ asset('assets/img/default.png') }}" class="card-img-top"
                                        alt="Default">
                                @endif
                                <div class="card-body py-2">
                                    <h5 class="card-title fw-bold text-primary">{{ Str::limit($bahan->name, 25, '...') }}</h5>
                                    <p class="card-text m-0 p-0">Stok: <span
                                            class="badge bg-success">{{ $bahan->stock }}
                                            {{ $bahan->unit }}</span></p>
                                    <p class="card-text m-0 p-0 text-muted">Kadaluarsa: {{ $bahan->date_expired }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada data bahan.</p>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $bahans->withQueryString()->links() }}
                </div>
            @endif
        @endif

        @if (request('type') === 'ruangan')
            {{-- Ruangan Section --}}
            <h4 class="mt-5 mb-3">Ruangan</h4>
            <div class="row">
                @forelse ($ruangans as $ruangan)
                    <div class="col-lg-2 col-md-2 col-sm-4 col-6 mb-4">
                        <div class="card h-100">
                            @if ($ruangan->foto_ruangan)
                                <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}" class="card-img-top"
                                    alt="{{ $ruangan->name }}">
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" class="card-img-top"
                                    alt="Default">
                            @endif
                            <div class="card-body py-2">
                                <h5 class="card-title fw-bold text-primary">{{ Str::limit($ruangan->name, 25, '...') }}</h5>
                                <p class="card-text m-0 p-0 text-muted">Gedung: {{ $ruangan->gedung }}</p>
                                <p class="card-text m-0 p-0 text-muted">Lantai: {{ $ruangan->lantai }}</p>
                                <p class="card-text m-0 p-0">Kapasitas: <span
                                        class="badge bg-success">{{ $ruangan->kapasitas }}</span></p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada data ruangan.</p>
                @endforelse
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $ruangans->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
