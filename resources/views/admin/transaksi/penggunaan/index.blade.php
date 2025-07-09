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
                            @php
                                $tooltip = "<strong>Nomor Seri alat yang digunakan</strong><ul style='margin:0 0 0 18px;padding:0;'>";
                                foreach(array_slice($serials,0,5) as $serial) {
                                    $tooltip .= "<li>{$serial}</li>";
                                }
                                if(count($serials) > 5) {
                                    $tooltip .= "<li><em>dan " . (count($serials)-5) . " lainnya</em></li>";
                                }
                                $tooltip .= "</ul>";
                            @endphp
                            <span>
                                {{ $name }}
                                <span class="info-icon" tabindex="0" data-bs-toggle="tooltip" data-bs-html="true" title="{!! $tooltip !!}">&#8505;</span>
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
                                    onclick="openValidasiModal('{{ $user->name }}', '{{ $laporan->waktu_mulai }}', '{{ $laporan->waktu_selesai }}', '{{ $laporan->tujuan_penggunaan }}', `{{ implode(', ', array_keys($groupedItems)) }}`)">
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

    <!-- Modal Validasi Penggunaan (Modern) -->
    <div class="modal fade formValidate" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form id="formValidasiPenggunaan" action="{{ route('admin.validasi.penggunaan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" id="user_id_modal">
                <input type="hidden" name="waktu_mulai" id="waktu_mulai_modal">
                <input type="hidden" name="waktu_selesai" id="waktu_selesai_modal">
                <input type="hidden" name="tujuan_penggunaan" id="tujuan_penggunaan_modal">
                <input type="hidden" name="status_peminjaman" id="status_peminjaman_modal">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white" style="background: linear-gradient(90deg, #3b82f6 60%, #60a5fa 100%);">
                        <div>
                            <h5 class="modal-title mb-0">
                                <i class="bi bi-shield-check me-2"></i> Validasi Penggunaan
                            </h5>
                            <div class="small text-white-50">Pastikan data penggunaan sudah benar sebelum konfirmasi.</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 p-3 bg-light border rounded d-flex align-items-center">
                            <i class="bi bi-info-circle-fill text-info fs-3 me-3"></i>
                            <div>
                                <div class="fw-bold mb-1">Proses Validasi Penggunaan</div>
                                <div class="text-muted small">Pastikan data penggunaan, waktu, dan tujuan sudah sesuai sebelum melakukan validasi. Anda dapat mengunggah foto alat sebelum digunakan dan menambahkan catatan jika diperlukan.</div>
                            </div>
                        </div>
                        <!-- Ringkasan penggunaan -->
                        <div class="mb-3 p-3 bg-white border rounded shadow-sm d-flex align-items-center">
                            <i class="bi bi-box-seam text-primary fs-2 me-3"></i>
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <strong>Pengguna:</strong> <span id="ringkasan_user_penggunaan">-</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong></strong> <span id="ringkasan_waktu_penggunaan">-</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Tujuan:</strong> <span id="ringkasan_tujuan_penggunaan">-</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Item:</strong> <span id="ringkasan_item_penggunaan">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Form validasi -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="mb-2"><span class="step-circle">1</span> <span class="fw-semibold">Catatan</span></div>
                                <textarea name="catatan" class="form-control" rows="4" placeholder="Catatan validasi penggunaan..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2"><span class="step-circle">2</span> <span class="fw-semibold">Upload Foto (Opsional)</span></div>
                                <input type="file" name="gambar_sebelum" class="form-control" accept="image/*" onchange="previewGambarSebelum(event)">
                                <div class="form-text">Upload foto alat sebelum digunakan (jika perlu).</div>
                                <div class="mt-2" id="previewGambarSebelum"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-success btn-lg px-4 me-2" onclick="submitValidasi('Diterima')" data-bs-toggle="tooltip" data-bs-placement="top" title="Setujui penggunaan ini.">
                            <i class="bi bi-check-circle me-1"></i> Setuju
                        </button>
                        <button type="button" class="btn btn-danger btn-lg px-4" onclick="submitValidasi('Ditolak')" data-bs-toggle="tooltip" data-bs-placement="top" title="Tolak penggunaan ini.">
                            <i class="bi bi-x-circle me-1"></i> Tolak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Saat tombol validasi diklik, set data ke modal dan ringkasan
        function openValidasiModal(userId, waktuMulai, waktuSelesai, tujuanPenggunaan, items) {
            document.getElementById('user_id_modal').value = userId;
            document.getElementById('waktu_mulai_modal').value = waktuMulai;
            document.getElementById('waktu_selesai_modal').value = waktuSelesai;
            document.getElementById('tujuan_penggunaan_modal').value = tujuanPenggunaan;
            document.getElementById('status_peminjaman_modal').value = '';
            $('#formValidasiPenggunaan')[0].reset();
            // Format waktu agar lebih jelas
            function formatWaktu(waktu) {
                if (!waktu) return '-';
                const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                const d = new Date(waktu.replace(' ', 'T'));
                if (isNaN(d)) return waktu;
                return `${d.getDate().toString().padStart(2,'0')} ${bulan[d.getMonth()]} ${d.getFullYear()} ${d.getHours().toString().padStart(2,'0')}:${d.getMinutes().toString().padStart(2,'0')}`;
            }
            document.getElementById('ringkasan_user_penggunaan').textContent = userId;
            document.getElementById('ringkasan_waktu_penggunaan').innerHTML = `<span class='me-2'><strong>Waktu Mulai:</strong> ${formatWaktu(waktuMulai)}</span><br><span><strong>Waktu Selesai:</strong> ${formatWaktu(waktuSelesai)}</span>`;
            document.getElementById('ringkasan_tujuan_penggunaan').textContent = tujuanPenggunaan;
            document.getElementById('ringkasan_item_penggunaan').textContent = items || '-';
            document.getElementById('previewGambarSebelum').innerHTML = '';
            $('.formValidate').modal('show');
        }
        // Preview gambar sebelum
        function previewGambarSebelum(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewGambarSebelum').innerHTML = `<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-width: 180px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('previewGambarSebelum').innerHTML = '';
            }
        }
        // Submit form dengan status sesuai tombol (Setuju/Tolak)
        function submitValidasi(status) {
            document.getElementById('status_peminjaman_modal').value = status;
            document.getElementById('formValidasiPenggunaan').submit();
        }
        // Aktifkan tooltip pada tombol aksi DAN info-icon serial number
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
        .info-icon {
            color: #3498db;
            margin-left: 4px;
            font-size: 1.1em;
            vertical-align: middle;
            cursor: pointer;
        }
    </style>

</x-admin-table>
