<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Validasi Penggunaan
    </x-slot>

    @include('components.alert')

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <x-slot name="autoValidate">
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('admin.auto-validate.penggunaan') }}" method="POST" id="autoValidateForm">
                @csrf
                @method('PUT')
                <div class="form-check form-switch">
                    <input type="hidden" name="autoValidate" value="0">
                    <input class="form-check-input" type="checkbox" id="autoValidate" name="autoValidate" value="1"
                        onchange="document.getElementById('autoValidateForm').submit()"
                        {{ $autoValidate->penggunaan ? 'checked' : '' }}>
                    <label class="form-check-label" for="autoValidate">Auto Validate</label>
                </div>
            </form>
        </div>
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
                <th>{{ __('Waktu Mulai') }}</th>
                <th>{{ __('Estimasi Pengembalian') }}</th>
                <th>{{ __('Status Validasi') }}</th>
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
                    <td>
                        {{ $laporan->alat->category->name ?? ($laporan->bahan->category->name ?? ($laporan->ruangan->category->name ?? '-')) }}
                    </td>
                    <td>{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}</td>
                    <td>{{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}
                    </td>
                    <td>{{ $laporan->waktu_mulai ?? '-' }}</td>
                    <td>{{ $laporan->waktu_selesai ?? '-' }}</td>
                    <td>
                        @if ($laporan->status_peminjaman == 'Diterima')
                            <span class="badge badge-success">Diterima</span>
                        @elseif ($laporan->status_peminjaman == 'Menunggu')
                            <span class="badge badge-warning">Menunggu</span>
                        @elseif ($laporan->status_peminjaman == 'Ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $laporan->catatan ?? '-' }}</td>
                    <td class="manage-row text-center">
                        @if ($laporan->status_peminjaman == 'Diterima' || $laporan->status_peminjaman == 'Ditolak')
                            <span class="badge badge-success">Selesai</span>
                        @else
                            @can('penggunaan-transaksi')
                                <button role="button" class="btn btn-xs m-1 btn-primary"
                                    onclick="openValidasiModal({{ $laporan->id }})">
                                    <i class="fas fa-check"></i> Validasi
                                </button>
                            @endcan
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

    <!-- Modal Validasi Penggunaan -->
    <div class="modal fade formValidate" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="formValidasiPenggunaan" action="{{ route('admin.validasi.penggunaan') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="laporan_id" id="laporan_id_modal">
                <input type="hidden" name="status_peminjaman" id="status_peminjaman_modal">
                <div class="modal-content p-3">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="catatan">Catatan</label>
                                <textarea name="catatan" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label for="gambar_sebelum">Gambar Alat Sebelum <small
                                        class="text-muted">(opsional)</small></label>
                                <input type="file" name="gambar_sebelum" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-success"
                            onclick="submitValidasi('Diterima')">Setuju</button>
                        <button type="button" class="btn btn-danger" onclick="submitValidasi('Ditolak')">Tolak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Saat tombol validasi diklik, set laporan_id ke modal
        function openValidasiModal(laporanId) {
            document.getElementById('laporan_id_modal').value = laporanId;
            document.getElementById('status_peminjaman_modal').value = ''; // reset
            $('#formValidasiPenggunaan')[0].reset(); // reset form
            $('.formValidate').modal('show');
        }

        // Submit form dengan status sesuai tombol (Setuju/Tolak)
        function submitValidasi(status) {
            document.getElementById('status_peminjaman_modal').value = status;
            document.getElementById('formValidasiPenggunaan').submit();
        }
    </script>

</x-admin-table>
