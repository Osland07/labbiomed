<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Validasi Pengajuan Peminjaman
    </x-slot>

    @include('components.alert')

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <x-slot name="autoValidate">
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('admin.auto-validate.peminjaman') }}" method="POST" id="autoValidateForm">
                @csrf
                @method('PUT')
                <div class="form-check form-switch">
                    <input type="hidden" name="autoValidate" value="0">
                    <input class="form-check-input" type="checkbox" id="autoValidate" name="autoValidate" value="1"
                        onchange="document.getElementById('autoValidateForm').submit()"
                        {{ $autoValidate->peminjaman ? 'checked' : '' }}>
                    <label class="form-check-label" for="autoValidate">Auto Validate</label>
                </div>
            </form>
        </div>
    </x-slot>

    <!-- Table -->
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Pemohon</th>
                <th rowspan="2">Keperluan</th>
                <th colspan="2">Peralatan</th>
                <th rowspan="2">Durasi Kegiatan</th>
                <th rowspan="2">Surat</th>
                <th rowspan="2">Status Validasi</th>
                <th rowspan="2">Catatan Admin</th>
                <th rowspan="2">Aksi</th>
            </tr>
            <tr>
                <th>Alat</th>
                <th>Jumlah</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td style="max-width: 220px; word-wrap: break-word; white-space: normal;">
                        <span>{{ $laporan->user->name ?? '-' }}</span><br>
                        {{ $laporan->user->nim ?? '-' }}<br>
                        <a href="https://wa.me/+62{{ $laporan->user->no_hp ?? '-' }}">
                            {{ $laporan->user->no_hp ?? '-' }}
                            <i class="fa fa-whatsapp text-success"></i></a><br>
                        <a href="mailto:{{ $laporan->user->email ?? '-' }}">
                            {{ $laporan->user->email ?? '-' }}
                            <i class="fa fa-envelope text-primary"></i>
                        </a>
                    </td>
                    <td>{{ $laporan->tujuan_peminjaman ?? '-' }}</td>
                    <td style="min-width: 100px; word-wrap: break-word; white-space: normal;">
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
                    <td style="max-width: 220px; word-wrap: break-word; white-space: normal;">
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
                        @if ($laporan->surat != null)
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#pdfModal-{{ $laporan->id }}">
                                Lihat <i class="fa fa-file-pdf text-white"></i>
                            </button>
                        @else
                            <span class="text-danger">Surat belum diunggah!</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->status_validasi == 'Diterima')
                            <span class="badge badge-success">Diterima</span>
                        @elseif ($laporan->status_validasi == 'Menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif ($laporan->status_validasi == 'Ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $laporan->catatan ?? '-' }}</td>
                    <td class="manage-row text-center">
                        @if ($laporan->status_validasi == 'Diterima' || $laporan->status_validasi == 'Ditolak')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($laporan->status_validasi == 'Menunggu')
                            @can('peminjaman-transaksi')
                                <button role="button" class="btn btn-xs m-1 btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#validasiModal-{{ $laporan->id }}">
                                    <i class="fas fa-check"></i> Validasi
                                </button>
                            @endcan
                        @endif
                    </td>
                </tr>
                @if ($laporan->surat)
                    <div class="modal fade" id="pdfModal-{{ $laporan->id }}" tabindex="-1"
                        aria-labelledby="pdfModalLabel-{{ $laporan->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel-{{ $laporan->id }}">Surat Peminjaman
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe src="{{ asset('storage/surat/' . $laporan->surat) }}" frameborder="0"
                                        width="100%" height="600px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Modal Validasi Peminjaman (per laporan) -->
                <div class="modal fade formValidate" id="validasiModal-{{ $laporan->id }}" tabindex="-1"
                    role="dialog" aria-labelledby="validasiModalLabel-{{ $laporan->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('admin.validasi.peminjaman', $laporan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="status_validasi"
                                id="status_validasi_modal_{{ $laporan->id }}">
                            <div class="modal-content p-3">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="catatan">Catatan</label>
                                            <textarea name="catatan" class="form-control" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-end">
                                    <button type="button" class="btn btn-success"
                                        onclick="document.getElementById('status_validasi_modal_{{ $laporan->id }}').value='Diterima'; this.form.submit();">Setuju</button>
                                    <button type="button" class="btn btn-danger"
                                        onclick="document.getElementById('status_validasi_modal_{{ $laporan->id }}').value='Ditolak'; this.form.submit();">Tolak</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
