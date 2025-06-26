<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Laporan Pengajuan Peminjaman
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Button Export -->
    <x-slot name="export">
        @include('admin.laporan.peminjaman.export')
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
                <th rowspan="2">Status Kegiatan</th>
                <th rowspan="2">Catatan Admin</th>
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
                    <td>
                        @if ($laporan->status_kegiatan == 'Sedang Berjalan')
                            <span class="badge badge-warning">Sedang Berjalan</span>
                        @elseif ($laporan->status_kegiatan == 'Selesai')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $laporan->catatan ?? '-' }}</td>
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
            @empty
                <tr>
                    <td colspan="10" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
