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
                                    onclick="openPengembalianModal({{ $laporan->id }}, '{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}', '{{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}', '{{ $laporan->user->name ?? '-' }}', '{{ $laporan->waktu_selesai ?? '-' }}')">
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
            <form id="formValidasiPengembalian" action="{{ route('admin.validasi.pengembalian') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="laporan_id" id="laporan_id_pengembalian">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="background: linear-gradient(90deg, #3b82f6 60%, #60a5fa 100%);">
                        <div>
                            <h5 class="modal-title mb-0">
                                <i class="bi bi-shield-check me-2"></i> Validasi Pengembalian Alat
                            </h5>

                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 p-3 bg-light border rounded d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-info fs-3 me-3"></i>
                            <div>
                                <div class="fw-bold mb-1">Proses Validasi Pengembalian</div>
                                <div class="text-muted small">Pastikan data alat dan kondisi pengembalian sudah sesuai sebelum melakukan validasi. Anda dapat mengunggah foto alat setelah dikembalikan dan menambahkan catatan jika diperlukan.</div>
                            </div>
                        </div>
                        <!-- Ringkasan alat -->
                        <div class="mb-3 p-3 bg-white border rounded shadow-sm d-flex align-items-center">
                            <i class="bi bi-box-seam text-primary fs-2 me-3"></i>
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <strong>Nama Alat:</strong> <span id="ringkasan_nama_alat">-</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Nomor Seri:</strong> <span id="ringkasan_nomor_seri">-</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Pengguna:</strong> <span id="ringkasan_pengguna">-</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Estimasi Kembali:</strong> <span id="ringkasan_estimasi">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Form validasi -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="mb-2"><span class="step-circle">1</span> <span class="fw-semibold">Pilih Kondisi</span></div>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="kondisi_setelah" value="Baik" id="kondisi_baik" autocomplete="off" checked>
                                    <label class="btn btn-outline-success" for="kondisi_baik"><span class="badge bg-success">Baik</span></label>
                                    <input type="radio" class="btn-check" name="kondisi_setelah" value="Rusak" id="kondisi_rusak" autocomplete="off">
                                    <label class="btn btn-outline-danger" for="kondisi_rusak"><span class="badge bg-danger">Rusak</span></label>
                                </div>
                                <div class="form-text">Pilih kondisi alat setelah dikembalikan.</div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2"><span class="step-circle">2</span> <span class="fw-semibold">Upload Foto (Opsional)</span></div>
                                <input type="file" class="form-control" name="gambar_setelah" accept="image/*" onchange="previewGambarSetelah(event)">
                                <div class="form-text">Upload foto alat setelah dikembalikan (jika perlu).</div>
                                <div class="mt-2" id="previewGambarSetelah"></div>
                            </div>
                        </div>
                        <div class="mt-3" id="detailKerusakanContainer" style="display: none;">
                            <div class="p-3 rounded" style="background: #fff0f3; border: 1px solid #fca5a5;">
                                <div class="mb-2"><span class="step-circle">3</span> <span class="fw-semibold text-danger">Detail Kerusakan</span></div>
                                <textarea name="deskripsi_kerusakan" class="form-control" rows="3" placeholder="Jelaskan detail kerusakan..."></textarea>
                                <small class="text-danger">* Wajib diisi jika kondisi rusak</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="fw-semibold">Keterangan Tambahan (Opsional)</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Catatan umum mengenai pengembalian..."></textarea>
                            <div class="form-text">Isi jika ada catatan khusus terkait pengembalian alat.</div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="submit" class="btn btn-success btn-lg px-4" id="btnKonfirmasi" data-bs-toggle="tooltip" data-bs-placement="top" title="Pastikan data sudah benar sebelum konfirmasi.">
                            <i class="bi bi-check-circle me-1"></i> Konfirmasi Pengembalian
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Buka modal dan isi ID laporan serta ringkasan alat
        function openPengembalianModal(laporanId, namaAlat = '-', nomorSeri = '-', pengguna = '-', estimasi = '-') {
            $('#laporan_id_pengembalian').val(laporanId);
            $('#formValidasiPengembalian')[0].reset();
            $('#detailKerusakanContainer').hide();
            $('.formValidate').modal('show');
            // Isi ringkasan
            $('#ringkasan_nama_alat').text(namaAlat);
            $('#ringkasan_nomor_seri').text(nomorSeri);
            $('#ringkasan_pengguna').text(pengguna);
            $('#ringkasan_estimasi').text(estimasi);
            $('#previewGambarSetelah').html('');
        }

        // Preview gambar setelah
        function previewGambarSetelah(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewGambarSetelah').html(`<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-width: 180px;">`);
                };
                reader.readAsDataURL(file);
            } else {
                $('#previewGambarSetelah').html('');
            }
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

        // Aktifkan tooltip pada tombol konfirmasi
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <style>
        .step-circle {
            display: inline-block;
            width: 24px;
            height: 24px;
            line-height: 24px;
            border-radius: 50%;
            background: #3b82f6;
            color: #fff;
            text-align: center;
            font-weight: bold;
            margin-right: 6px;
            font-size: 14px;
        }
    </style>

</x-admin-table>
