<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Validasi Pengajuan
    </x-slot>

    @include('components.alert')

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Table -->
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th rowspan="2" class="text-center">No</th>
                <th rowspan="2" class="text-center">Pengaju</th>
                <th rowspan="2" class="text-center">Judul Penelitian</th>
                <th rowspan="2" class="text-center">Tujuan</th>
                <th colspan="2" class="text-center">Peralatan</th>
                <th rowspan="2" class="text-center">Durasi</th>
                <th rowspan="2" class="text-center">Validasi Laboran</th>
                <th rowspan="2" class="text-center">Validasi Koordinator</th>
                <th rowspan="2" class="text-center">Aksi</th>
            </tr>
            <tr>
                <th class="text-center">Alat</th>
                <th class="text-center">Jumlah</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td>
                        <span>{{ $laporan->user->name ?? '-' }}</span>
                        <br> {{ $laporan->user->nim ?? '-' }}<br>
                        <a href="https://wa.me/+62{{ $laporan->user->no_hp ?? '-' }}">
                            {{ $laporan->user->no_hp ?? '-' }}
                            <i class="fa fa-whatsapp text-success"></i></a><br> <a
                            href="mailto:{{ $laporan->user->email ?? '-' }}">{{ $laporan->user->email ?? '-' }} <i
                                class="fa fa-envelope text-primary"></i></a>
                    </td>
                    <td>{{ $laporan->judul_penelitian ?? '-' }}</td>
                    <td>{{ $laporan->tujuan_peminjaman ?? '-' }}</td>
                    <td>
                        @php
                            $alatList = $laporan->alatList();
                            $groupedAlat = [];
                            foreach ($alatList as $alat) {
                                $baseName = preg_replace('/\\s+#\\d+$/', '', $alat->name);
                                if (!isset($groupedAlat[$baseName])) {
                                    $groupedAlat[$baseName] = 0;
                                }
                                $groupedAlat[$baseName]++;
                            }
                        @endphp
                        @foreach ($groupedAlat as $name => $qty)
                            {{ $name }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($groupedAlat as $name => $qty)
                            {{ $qty }}<br>
                        @endforeach
                    </td>
                    <td>
                        {{ $laporan->tgl_peminjaman ? \Carbon\Carbon::parse($laporan->tgl_peminjaman)->translatedFormat('d F Y') : '-' }}
                        -
                        {{ $laporan->tgl_pengembalian ? \Carbon\Carbon::parse($laporan->tgl_pengembalian)->translatedFormat('d F Y') : '-' }}
                    </td>
                    <td>
                        @if ($laporan->validated_by_laboran)
                            <span class="badge badge-success">✓ Divalidasi</span><br>
                            <small>{{ $laporan->laboran->name ?? '-' }}</small><br>
                            <small>{{ $laporan->validated_at_laboran ? \Carbon\Carbon::parse($laporan->validated_at_laboran)->format('d/m/Y H:i') : '-' }}</small>
                        @else
                            <span class="badge badge-warning">Belum Divalidasi</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->validated_by_koordinator)
                            <span class="badge badge-success">✓ Divalidasi</span><br>
                            <small>{{ $laporan->koordinator->name ?? '-' }}</small><br>
                            <small>{{ $laporan->validated_at_koordinator ? \Carbon\Carbon::parse($laporan->validated_at_koordinator)->format('d/m/Y H:i') : '-' }}</small>
                        @else
                            <span class="badge badge-warning">Belum Divalidasi</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->canBeValidatedByLaboran() && auth()->user()->hasRole('Laboran'))
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#validasiLaboranModal-{{ $laporan->id }}">
                                <i class="fas fa-check"></i> Validasi Laboran
                            </button>
                        @elseif ($laporan->canBeValidatedByKoordinator() && auth()->user()->hasRole('Koordinator Laboratorium'))
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#validasiKoordinatorModal-{{ $laporan->id }}">
                                <i class="fas fa-check"></i> Validasi Koordinator
                            </button>
                        @else
                            <span class="text-muted">Tidak dapat divalidasi</span>
                        @endif
                    </td>
                </tr>

                {{-- Modal Validasi Laboran --}}
                @if ($laporan->canBeValidatedByLaboran() && auth()->user()->hasRole('Laboran'))
                    <tr>
                        <td colspan="100">
                            <div class="modal fade" id="validasiLaboranModal-{{ $laporan->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="validasiLaboranModalLabel-{{ $laporan->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('admin.validasi.laboran', $laporan->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Validasi Laboran - {{ $laporan->judul_penelitian }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status Validasi</label>
                                                    <select name="status_validasi" class="form-select" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="Diterima">Diterima</option>
                                                        <option value="Ditolak">Ditolak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan</label>
                                                    <textarea name="catatan" class="form-control" rows="3" 
                                                        placeholder="Berikan catatan validasi..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Validasi</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif

                {{-- Modal Validasi Koordinator --}}
                @if ($laporan->canBeValidatedByKoordinator() && auth()->user()->hasRole('Koordinator Laboratorium'))
                    <tr>
                        <td colspan="100">
                            <div class="modal fade" id="validasiKoordinatorModal-{{ $laporan->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="validasiKoordinatorModalLabel-{{ $laporan->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ route('admin.validasi.koordinator', $laporan->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Validasi Koordinator - {{ $laporan->judul_penelitian }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status Validasi</label>
                                                    <select name="status_validasi" class="form-select" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="Diterima">Diterima</option>
                                                        <option value="Ditolak">Ditolak</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan</label>
                                                    <textarea name="catatan" class="form-control" rows="3" 
                                                        placeholder="Berikan catatan validasi..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Simpan Validasi</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="100" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
