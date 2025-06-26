<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Validasi Pengembalian
    </x-slot>

    @include('components.alert')

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Table -->
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th class="text-center">{{ __('Pengguna') }}</th>
                <th>{{ __('Tujuan Penggunaan') }}</th>
                <th>{{ __('Nama Alat/Bahan/Ruangan') }}</th>
                <th>{{ __('Nomor Seri') }}</th>
                <th>{{ __('Estimasi Pengembalian') }}</th>
                <th>{{ __('Waktu Pengembalian') }}</th>
                <th>{{ __('Status Pengembalian') }}</th>
                <th>{{ __('Kondisi Saat Pengembalian') }}</th>
                <th>{{ __('Catatan Validator') }}</th>
                <th class="text-center">{{ __('Aksi') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td class="text-center" style="max-width: 220px; word-wrap: break-word; white-space: normal;">
                        <span>{{ $laporan->user->name ?? '-' }}</span>
                        <br> {{ $laporan->user->nim ?? '-' }}<br>
                        <a href="https://wa.me/+62{{ $laporan->user->no_hp ?? '-' }}">
                            {{ $laporan->user->no_hp ?? '-' }}
                            <i class="fa fa-whatsapp text-success"></i></a><br> <a
                            href="mailto:{{ $laporan->user->email ?? '-' }}">{{ $laporan->user->email ?? '-' }} <i
                                class="fa fa-envelope text-primary"></i></a>
                    </td>
                    <td>{{ $laporan->tujuan_penggunaan ?? '-' }}</td>
                    <td>{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}</td>
                    <td>{{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}
                    </td>
                    <td>{{ $laporan->waktu_selesai ?? '-' }}</td>
                    <td>{{ $laporan->tgl_pengembalian ?? '-' }}</td>
                    <td>
                        @if ($laporan->status_pengembalian == 'Belum Dikembalikan')
                            <span class="badge badge-warning">Belum Dikembalikan</span>
                        @elseif ($laporan->status_pengembalian == 'Sudah Dikembalikan')
                            <span class="badge badge-success">Sudah Dikembalikan</span>
                        @endif
                    </td>
                    <td>
                        @if ($laporan->kondisi_setelah == 'Baik')
                            <span class="badge bg-success">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                        @elseif($laporan->kondisi_setelah == 'Rusak')
                            <span class="badge bg-danger">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                        @else
                            <span class="badge bg-warning">{{ $laporan->kondisi_setelah ?? '-' }}</span>
                        @endif
                    </td>
                    <td>{{ $laporan->catatan ?? '-' }}</td>
                    <td class="manage-row text-center">
                        @if ($laporan->status_pengembalian == 'Sudah Dikembalikan')
                            <span class="badge badge-success">Selesai</span>
                        @elseif($laporan->status_pengembalian == 'Belum Dikembalikan')
                            @can('pengembalian-transaksi')
                                <button role="button" class="btn btn-xs m-1 btn-primary"
                                    onclick="openPengembalianModal({{ $laporan->id }})">
                                    <i class="fas fa-check"></i> Validasi
                                </button>
                            @endcan
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

    <!-- Modal Validasi Pengembalian -->
    <div class="modal fade formValidate" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formValidasiPengembalian" action="{{ route('admin.validasi.pengembalian') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="laporan_id" id="laporan_id_pengembalian">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Validasi Pengembalian Alat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row bg-light p-3 rounded">
                            <div class="col-md-6">
                                <label for="kondisi_setelah">Pilih Kondisi:</label><br>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="kondisi_setelah" value="Baik"
                                        id="kondisi_baik" autocomplete="off" checked>
                                    <label class="btn btn-outline-success" for="kondisi_baik">Baik</label>

                                    <input type="radio" class="btn-check" name="kondisi_setelah" value="Rusak"
                                        id="kondisi_rusak" autocomplete="off">
                                    <label class="btn btn-outline-danger" for="kondisi_rusak">Rusak</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="gambar_setelah">Gambar Alat Setelah (opsional)</label>
                                <input type="file" class="form-control" name="gambar_setelah" accept="image/*">
                            </div>
                        </div>

                        <div class="mt-3" id="detailKerusakanContainer" style="display: none;">
                            <label class="text-danger">Detail Kerusakan:</label>
                            <textarea name="deskripsi_kerusakan" class="form-control" rows="3" placeholder="Jelaskan detail kerusakan..."></textarea>
                            <small class="text-danger">* Wajib diisi jika kondisi rusak</small>
                        </div>

                        <div class="mt-3">
                            <label>Keterangan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan umum mengenai pengembalian..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="submit" class="btn btn-success" id="btnKonfirmasi">Konfirmasi
                            Pengembalian</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Buka modal dan isi ID laporan
        function openPengembalianModal(laporanId) {
            $('#laporan_id_pengembalian').val(laporanId);
            $('#formValidasiPengembalian')[0].reset();
            $('#detailKerusakanContainer').hide();
            $('.formValidate').modal('show');
        }

        // Toggle kerusakan detail
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('kondisi_baik').addEventListener('change', function() {
                document.getElementById('detailKerusakanContainer').style.display = 'none';
            });

            document.getElementById('kondisi_rusak').addEventListener('change', function() {
                document.getElementById('detailKerusakanContainer').style.display = 'block';
            });
        });
    </script>


</x-admin-table>
