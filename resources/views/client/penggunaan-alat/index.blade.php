<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Penggunaan Alat
    </x-slot>

    @include('components.alert')

    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Flatpickr Time Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($alats->isEmpty())
        <div class="text-danger mb-3">
            <p class="mb-3">Tidak ada alat yang tersedia, silahkan ajukan peminjaman alat terlebih dahulu.</p>
            <a href="{{ route('client.pengajuan-peminjaman.index') }}" class="btn btn-primary"><i
                    class="fas fa-file-signature"></i> Ajukan Peminjaman Alat</a>
        </div>
    @endif

    <div id="alatGrid" class="row g-4">
        @php
            $compactedAlat = [];
            foreach ($alats as $alat) {
                $baseName = preg_replace('/\s+#\d+$/', '', $alat->name);
                if (!isset($compactedAlat[$baseName])) {
                    $compactedAlat[$baseName] = [];
                }
                $compactedAlat[$baseName][] = $alat;
            }
        @endphp
        @foreach ($compactedAlat as $baseName => $group)
            <div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm">
                    <img src="{{ $group[0]->img ? '/storage/' . $group[0]->img : '/assets/img/default.png' }}"
                        class="card-img-top object-fit-cover" style="height: 180px;" alt="{{ $baseName }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">{{ $baseName }}</h5>
                        <p class="card-text text-muted mb-1">{{ $group[0]->ruangan->name ?? '-' }}</p>
                        <p class="card-text mb-1">Total: <span
                                class="fw-semibold text-primary">{{ count($group) }}</span></p>
                        <p class="card-text mb-2">Tersedia: <span
                                class="fw-semibold text-success">{{ collect($group)->where('status', 'Tersedia')->count() }}</span>
                        </p>
                        <div class="mt-auto">
                            <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#modalPenggunaan" data-basename="{{ $baseName }}">Ajukan
                                Penggunaan</button>
                            <button class="btn btn-secondary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#modalDetail" data-basename="{{ $baseName }}">Detail</button>
                            <button class="btn btn-success w-100" data-bs-toggle="modal"
                                data-bs-target="#modalKembalikan"
                                data-basename="{{ $baseName }}">Kembalikan</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Penggunaan Alat -->
    <div class="modal fade" id="modalPenggunaan" tabindex="-1" aria-labelledby="modalPenggunaanLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPenggunaanAlat" method="POST" action="{{ route('client.penggunaan-alat.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPenggunaanLabel">Ajukan Penggunaan Alat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="alatIdContainer"></div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tujuan Penggunaan<span
                                    class="text-danger">*</span></label>
                            <textarea name="tujuan_penggunaan" id="tujuanPenggunaanInput" class="form-control" required
                                placeholder="Jelaskan tujuan penggunaan alat ini..."></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Tanggal Mulai<span class="text-danger">*</span></label>
                                <input type="text" name="waktu_mulai" id="waktuMulaiInput" class="form-control"
                                    required placeholder="Pilih tanggal">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Tanggal Selesai<span class="text-danger">*</span></label>
                                <input type="text" name="waktu_selesai" id="waktuSelesaiInput" class="form-control"
                                    required placeholder="Pilih tanggal">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah<span class="text-danger">*</span></label>
                            <input type="number" min="1" name="qty" id="qtyInput" class="form-control"
                                required>
                            <div class="form-text">Tersedia: <span id="maxQtyText"></span> unit</div>
                        </div>
                        <div id="errorMessage" class="invalid-feedback d-block" style="display:none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan Penggunaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Alat -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="detailContent"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kembalikan Alat (dummy, implement as needed) -->
    <div class="modal fade" id="modalKembalikan" tabindex="-1" aria-labelledby="modalKembalikanLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKembalikanLabel">Kembalikan Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="kembalikanContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const allAlats = @json($alats);
        const compactedAlat = {};
        allAlats.forEach(alat => {
            const baseName = alat.name.replace(/\s+#\d+$/, '');
            if (!compactedAlat[baseName]) compactedAlat[baseName] = [];
            compactedAlat[baseName].push(alat);
        });

        let modalBaseName = '';
        let modalMaxQty = 1;
        let modalAlatIds = [];

        document.addEventListener('DOMContentLoaded', function() {
            // === Flatpickr Init ===
            flatpickr('#waktuMulaiInput', {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                time_24hr: true,
                minDate: 'today',
                onChange: function(selectedDates, dateStr) {
                    document.getElementById('waktuSelesaiInput')._flatpickr.set('minDate', dateStr);
                }
            });

            flatpickr('#waktuSelesaiInput', {
                enableTime: true,
                dateFormat: 'Y-m-d H:i',
                time_24hr: true,
                minDate: 'today',
            });

            // === Modal Penggunaan - Saat dibuka ===
            const modalPenggunaan = document.getElementById('modalPenggunaan');
            modalPenggunaan.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const baseName = button.getAttribute('data-basename');
                modalBaseName = baseName;

                const group = compactedAlat[baseName] || [];
                modalAlatIds = group.filter(a => a.status === 'Tersedia').map(a => a.id);
                modalMaxQty = modalAlatIds.length;

                document.getElementById('qtyInput').value = 1;
                document.getElementById('qtyInput').setAttribute('max', modalMaxQty);
                document.getElementById('maxQtyText').textContent = modalMaxQty;
                document.getElementById('tujuanPenggunaanInput').value = '';
                document.getElementById('waktuMulaiInput').value = '';
                document.getElementById('waktuSelesaiInput').value = '';
                document.getElementById('errorMessage').style.display = 'none';

                // Kosongkan alat_id container
                document.getElementById('alatIdContainer').innerHTML = '';
            });

            // === Validasi Jumlah (qty)
            document.getElementById('qtyInput').addEventListener('input', function() {
                let val = parseInt(this.value);
                if (val > modalMaxQty) this.value = modalMaxQty;
                if (val < 1) this.value = 1;
            });

            // === Handle Submit Form Penggunaan
            document.getElementById('formPenggunaanAlat').addEventListener('submit', function(e) {
                const tujuan = document.getElementById('tujuanPenggunaanInput').value.trim();
                const mulai = document.getElementById('waktuMulaiInput').value;
                const selesai = document.getElementById('waktuSelesaiInput').value;
                const qty = parseInt(document.getElementById('qtyInput').value);
                const errorDiv = document.getElementById('errorMessage');
                const alatIdContainer = document.getElementById('alatIdContainer');

                // Reset error
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';

                if (!tujuan || !mulai || !selesai || !qty) {
                    errorDiv.textContent = 'Semua field wajib diisi.';
                    errorDiv.style.display = 'block';
                    e.preventDefault();
                    return;
                }

                if (qty < 1 || qty > modalMaxQty) {
                    errorDiv.textContent = 'Jumlah tidak valid.';
                    errorDiv.style.display = 'block';
                    e.preventDefault();
                    return;
                }

                if (selesai < mulai) {
                    errorDiv.textContent = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
                    errorDiv.style.display = 'block';
                    e.preventDefault();
                    return;
                }

                // === Set input alat_id[] ke dalam form secara dinamis ===
                alatIdContainer.innerHTML = '';
                modalAlatIds.slice(0, qty).forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'alat_id[]';
                    input.value = id;
                    alatIdContainer.appendChild(input);
                });
            });

            // === Modal Detail
            document.getElementById('modalDetail').addEventListener('show.bs.modal', function(event) {
                const baseName = event.relatedTarget.getAttribute('data-basename');
                const group = compactedAlat[baseName] || [];
                let html =
                    `<div class="mb-3"><strong>Ringkasan:</strong><br>
                        Total Alat: <span>${group.length}</span><br>
                        Jumlah Baik: <span class="text-success">${group.filter(a => a.condition === 'Baik').length}</span><br>
                        Jumlah Rusak: <span class="text-danger">${group.filter(a => a.condition === 'Rusak').length}</span><br>
                        Jumlah Tersedia: <span class="text-primary">${group.filter(a => a.status === 'Tersedia').length}</span></div>`;
                html +=
                    `<div class="table-responsive"><table class="table table-bordered table-sm"><thead><tr><th>Nama Alat</th><th>Kondisi</th><th>Status</th><th>Lokasi</th></tr></thead><tbody>`;
                group.forEach(alat => {
                    html +=
                        `<tr><td>${alat.name}</td><td>${alat.condition}</td><td>${alat.status}</td><td>${alat.ruangan?.name || '-'}</td></tr>`;
                });
                html += `</tbody></table></div>`;
                document.getElementById('detailContent').innerHTML = html;
            });

            // === Modal Kembalikan Alat
            document.getElementById('modalKembalikan').addEventListener('show.bs.modal', function(event) {
                const baseName = event.relatedTarget.getAttribute('data-basename');
                const group = compactedAlat[baseName] || [];

                let html =
                    `<div class="mb-3"><strong>Ringkasan:</strong><br>
                        Total Alat: <span>${group.length}</span><br>
                        Jumlah Baik: <span class="text-success">${group.filter(a => a.condition === 'Baik').length}</span><br>
                        Jumlah Rusak: <span class="text-danger">${group.filter(a => a.condition === 'Rusak').length}</span><br>
                        Jumlah Tersedia: <span class="text-primary">${group.filter(a => a.status === 'Tersedia').length}</span></div>`;
                html +=
                    `<div class="table-responsive"><table class="table table-bordered table-sm"><thead><tr><th>Nama Alat</th><th>Kondisi</th><th>Status</th><th>Lokasi</th><th>Aksi</th></tr></thead><tbody>`;
                group.forEach(alat => {
                    html +=
                        `<tr><td>${alat.name}</td><td>${alat.condition}</td><td>${alat.status}</td><td>${alat.ruangan?.name || '-'}</td><td>`;
                    if (alat.status !== 'Tersedia') {
                        html +=
                            `<button class='btn btn-sm btn-success btn-kembalikan-alat' data-alatid='${alat.id}'>Kembalikan</button>`;
                    } else {
                        html += `-`;
                    }
                    html += `</td></tr>`;
                });
                html += `</tbody></table></div>`;
                document.getElementById('kembalikanContent').innerHTML = html;
            });

            // === Proses Kembalikan Alat (AJAX)
            document.getElementById('kembalikanContent').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-kembalikan-alat')) {
                    const alatId = e.target.getAttribute('data-alatid');
                    if (confirm('Yakin ingin mengembalikan alat ini?')) {
                        fetch(`/client/penggunaan-alat/kembalikan/${alatId}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Alat berhasil dikembalikan!');
                                    location.reload();
                                } else {
                                    alert(data.message || 'Gagal mengembalikan alat.');
                                }
                            })
                            .catch(() => alert('Terjadi kesalahan saat mengembalikan alat.'));
                    }
                }
            });
        });
    </script>


</x-admin-layout>
