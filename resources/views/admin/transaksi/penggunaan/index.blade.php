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
                <th>{{ __('Item yang Digunakan') }}</th>
                <th>{{ __('Jumlah Item') }}</th>
                <th>{{ __('Waktu Mulai') }}</th>
                <th>{{ __('Estimasi Pengembalian') }}</th>
                <th>{{ __('Status Validasi') }}</th>
                <th>{{ __('Catatan Validator') }}</th>
                <th class="text-center">{{ __('Aksi') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $laporan)
                @php
                    $user = \App\Models\User::find($laporan->user_id);
                    $items = explode(',', $laporan->items);
                    $itemDetails = [];
                    
                    foreach ($items as $item) {
                        if (strpos($item, 'Alat:') !== false) {
                            $alatId = str_replace('Alat:', '', $item);
                            $alat = \App\Models\Alat::find($alatId);
                            if ($alat) {
                                $itemDetails[] = "Alat: {$alat->name} ({$alat->serial_number})";
                            }
                        } elseif (strpos($item, 'Bahan:') !== false) {
                            $bahanId = str_replace('Bahan:', '', $item);
                            $bahan = \App\Models\Bahan::find($bahanId);
                            if ($bahan) {
                                $itemDetails[] = "Bahan: {$bahan->name} ({$bahan->serial_number})";
                            }
                        } elseif (strpos($item, 'Ruangan:') !== false) {
                            $ruanganId = str_replace('Ruangan:', '', $item);
                            $ruangan = \App\Models\Ruangan::find($ruanganId);
                            if ($ruangan) {
                                $itemDetails[] = "Ruangan: {$ruangan->name} ({$ruangan->serial_number})";
                            }
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $laporans->firstItem() + $loop->index }}</td>
                    <td class="text-center" style="max-width: 220px; word-wrap: break-word; white-space: normal;">
                        <span>{{ $user->name ?? '-' }}</span>
                        <br> {{ $user->nim ?? '-' }}<br>
                        <a href="https://wa.me/+62{{ $user->no_hp ?? '-' }}">
                            {{ $user->no_hp ?? '-' }}
                            <i class="fa fa-whatsapp text-success"></i></a><br> <a
                            href="mailto:{{ $user->email ?? '-' }}">{{ $user->email ?? '-' }} <i
                                class="fa fa-envelope text-primary"></i></a>
                    </td>
                    <td>{{ $laporan->tujuan_penggunaan ?? '-' }}</td>
                    <td>
                        @php
                            // Group itemDetails by name, collect serials (tanpa prefix)
                            $groupedItems = [];
                            foreach ($itemDetails as $detail) {
                                if (preg_match('/^(Alat|Bahan|Ruangan): ([^(]+) \(([^)]+)\)$/', $detail, $m)) {
                                    $name = $m[2];
                                    $serial = $m[3];
                                    if (!isset($groupedItems[$name])) $groupedItems[$name] = [];
                                    $groupedItems[$name][] = $serial;
                                }
                            }
                        @endphp
                        @foreach ($groupedItems as $name => $serials)
                            <span class="tooltip-custom">
                                {{ $name }}
                                <span class="info-icon">&#8505;</span>
                                <span class="tooltiptext">
                                    <strong>Nomor Seri alat yang digunakan</strong>
                                    <ul>
                                        @foreach (array_slice($serials, 0, 5) as $serial)
                                            <li>{{ $serial }}</li>
                                        @endforeach
                                        @if (count($serials) > 5)
                                            <li><em>dan {{ count($serials) - 5 }} lainnya</em></li>
                                        @endif
                                    </ul>
                                </span>
                            </span>
                            <br>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $laporan->total_items }} item</span>
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
                                    onclick="openValidasiModal('{{ $laporan->user_id }}', '{{ $laporan->waktu_mulai }}', '{{ $laporan->waktu_selesai }}', '{{ $laporan->tujuan_penggunaan }}')">
                                    <i class="fas fa-check"></i> Validasi {{ $laporan->total_items }} Item
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
        <div class="modal-dialog modal-lg" role="document">
            <form id="formValidasiPenggunaan" action="{{ route('admin.validasi.penggunaan') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" id="user_id_modal">
                <input type="hidden" name="waktu_mulai" id="waktu_mulai_modal">
                <input type="hidden" name="waktu_selesai" id="waktu_selesai_modal">
                <input type="hidden" name="tujuan_penggunaan" id="tujuan_penggunaan_modal">
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
        // Saat tombol validasi diklik, set data ke modal
        function openValidasiModal(userId, waktuMulai, waktuSelesai, tujuanPenggunaan) {
            document.getElementById('user_id_modal').value = userId;
            document.getElementById('waktu_mulai_modal').value = waktuMulai;
            document.getElementById('waktu_selesai_modal').value = waktuSelesai;
            document.getElementById('tujuan_penggunaan_modal').value = tujuanPenggunaan;
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

    <style>
    .tooltip-custom {
      position: relative;
      display: inline-block;
    }
    .tooltip-custom .info-icon {
      color: #3498db;
      margin-left: 4px;
      font-size: 1.1em;
      vertical-align: middle;
      cursor: pointer;
    }
    .tooltiptext {
      visibility: hidden;
      min-width: 260px;
      max-width: 350px;
      background: #222;
      color: #fff;
      text-align: left;
      border-radius: 10px;
      padding: 16px 20px;
      position: absolute;
      z-index: 10;
      bottom: 130%;
      left: 50%;
      transform: translateX(-50%);
      opacity: 0;
      transition: opacity 0.3s;
      font-size: 1em;
      box-shadow: 0 2px 12px rgba(0,0,0,0.22);
      white-space: normal;
    }
    .tooltip-custom .info-icon:hover + .tooltiptext,
    .tooltip-custom .tooltiptext:hover {
      visibility: visible;
      opacity: 1;
    }
    .tooltiptext ul {
      margin: 0 0 0 18px;
      padding: 0;
    }
    .tooltiptext li {
      margin-bottom: 2px;
      font-size: 0.98em;
    }
    </style>

</x-admin-table>
