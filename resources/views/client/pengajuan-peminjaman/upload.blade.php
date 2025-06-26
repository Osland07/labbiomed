<x-admin-table>
    <x-slot name="title">
        Upload Surat
    </x-slot>

    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Keperluan</th>
                <th colspan="2">Peralatan</th>
                <th rowspan="2">Durasi Kegiatan</th>
                <th rowspan="2">Template Surat</th>
                <th rowspan="2">Status Validasi</th>
                <th rowspan="2">Status Kegiatan</th>
                <th rowspan="2">Catatan Admin</th>
                <th rowspan="2">Aksi</th>
                <th rowspan="2">Surat</th>
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
                    <td>{{ $laporan->tujuan_peminjaman ?? '-' }}</td>
                    <td>
                        @php
                            $alatList = $laporan->alatList();
                            $groupedAlat = [];
                            foreach ($alatList as $alat) {
                                $baseName = preg_replace('/\s+#\d+$/', '', $alat->name);
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
                    <td><a href="{{ route('client.pengajuan-peminjaman.generate-formulir', $laporan->id) }}"
                            target="_blank" class="btn btn-xs btn-success"><i class="fa fa-download"></i> Download</a>
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
                    <td>
                        @php
                            $today = \Carbon\Carbon::today();
                            $tglPengembalian = \Carbon\Carbon::parse($laporan->tgl_pengembalian);
                        @endphp

                        @if ($today->gt($tglPengembalian))
                            <span class="badge badge-success">Selesai</span>
                        @else
                            <span class="badge badge-warning">Sedang Berjalan</span>
                        @endif
                    </td>
                    <td>{{ $laporan->catatan ?? '-' }}</td>
                    <td>
                        @if ($laporan->surat == null)
                            @can('pengajuan-peminjaman-client')
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#uploadModal-{{ $laporan->id }}">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            @endcan
                        @else
                            <span class="badge badge-success">Sudah Upload</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->surat)
                            <button type="button" class="btn btn-primary btn-xs" data-bs-toggle="modal"
                                data-bs-target="#pdfModal-{{ $laporan->id }}">
                                Lihat <i class="fa fa-file-pdf text-white"></i>
                            </button>
                        @else
                            <span class="text-danger">Surat belum diunggah!</span>
                        @endif
                    </td>
                </tr>

                {{-- PDF Modal (lihat surat) --}}
                @if ($laporan->surat)
                    <tr>
                        <td colspan="100">
                            <div class="modal fade" id="pdfModal-{{ $laporan->id }}" tabindex="-1"
                                aria-labelledby="pdfModalLabel-{{ $laporan->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Surat Peminjaman</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="{{ asset('storage/surat/' . $laporan->surat) }}"
                                                frameborder="0" width="100%" height="600px"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif

                {{-- Upload Modal (unggah surat) --}}
                <tr>
                    <td colspan="100">
                        <div class="modal fade" id="uploadModal-{{ $laporan->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="uploadModalLabel-{{ $laporan->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{ route('client.pengajuan-peminjaman.storeUpload', $laporan->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content p-3">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Upload Surat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="surat">Upload Surat Peminjaman yang sudah disetujui (PDF: maks. 2MB)</label>
                                                <input type="file" name="surat" class="form-control" accept=".pdf"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-end">
                                            <button type="submit" class="btn btn-success">Upload</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}
</x-admin-table>
