<x-admin-table>
    <x-slot name="title">
        Pengajuan Saya
    </x-slot>

    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Informasi -->
    <div class="alert alert-info mb-3">
        <i class="fas fa-info-circle"></i>
        <strong>Informasi:</strong>
        <ul class="mb-0">
            <li>Harap menunggu validasi dari Laboran dan Koordinator Laboran sebelum mengajukan tanda tangan surat ke fakultas.</li>
            <li>Setelah divalidasi, silakan ajukan tanda tangan surat ke fakultas.</li>
            <li>Unggah kembali surat yang telah ditandatangani dengan ketentuan:
            <ul>
                <li>Format file: PDF</li>
                <li>Ukuran maksimal: 2MB</li>
            </ul>
            </li>
        </ul>
    </div>

    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Keperluan</th>
                <th colspan="2">Peralatan</th>
                <th rowspan="2">Durasi Kegiatan</th>
                <th rowspan="2">Status Validasi</th>
                <th rowspan="2">Surat Tervalidasi</th>
                <th rowspan="2">Catatan</th>
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
                    <td>
                        @include('components.status-validasi', ['laporan' => $laporan])
                    </td>
                    <td>
                        @if ($laporan->canGenerateSurat())
                            <a href="{{ route('client.pengajuan-peminjaman.generate-formulir', $laporan->id) }}"
                                target="_blank" class="btn btn-xs btn-success">
                                <i class="fa fa-download"></i> Download
                            </a>
                        @else
                            <span class="text-muted">Surat belum tersedia</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->status_validasi == 'Ditolak')
                            @if ($laporan->catatan_laboran)
                                <strong>Laboran:</strong> {{ $laporan->catatan_laboran }}<br>
                            @endif
                            @if ($laporan->catatan_koordinator)
                                <strong>Koordinator:</strong> {{ $laporan->catatan_koordinator }}
                            @endif
                        @else
                            {{ $laporan->catatan ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if ($laporan->canUploadSurat())
                            @can('pengajuan-peminjaman-client')
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#uploadModal-{{ $laporan->id }}">
                                    <i class="fas fa-upload"></i> Upload
                                </button>
                            @endcan
                        @elseif ($laporan->surat)
                            <span class="badge badge-success">Sudah Upload</span>
                        @else
                            <span class="text-muted">Belum dapat upload</span>
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
                @if ($laporan->canUploadSurat())
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
                                                    <label for="surat">Upload Surat Peminjaman yang sudah ditandatangani (PDF: maks. 2MB)</label>
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
