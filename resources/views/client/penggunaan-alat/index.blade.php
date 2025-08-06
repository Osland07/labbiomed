<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Penggunaan Alat
    </x-slot>

    @include('components.alert')

    <!-- Header dengan tombol penggunaan aktif -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Penggunaan Alat</h4>
            <p class="text-muted mb-0">Kelola penggunaan alat laboratorium</p>
        </div>
        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalPenggunaanAktif">
            <i class="bi bi-list-check me-2"></i>Lihat Penggunaan Aktif
        </button>
    </div>

    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Flatpickr Time Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($alats->isEmpty())
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 60vh;">
        <div class="mb-3">
            <!-- Bootstrap icon or SVG -->
            <svg width="64" height="64" fill="currentColor" class="bi bi-box-seam text-primary" viewBox="0 0 16 16">
                <path d="M8.21.5a1 1 0 0 0-.42 0l-6 1.5A1 1 0 0 0 1 3v9.5a1 1 0 0 0 .79.97l6 1.5a1 1 0 0 0 .42 0l6-1.5A1 1 0 0 0 15 12.5V3a1 1 0 0 0-.79-.97l-6-1.5zM2.5 3.5 8 5l5.5-1.5M8 5v9.5M1 3l6 1.5M15 3l-6 1.5"/>
            </svg>
        </div>
        <h3 class="fw-bold text-secondary mb-2">Belum Ada Alat Tersedia</h3>
        <p class="text-muted text-center mb-4" style="max-width: 400px;">
            Anda belum memiliki alat yang dapat digunakan. Silakan ajukan peminjaman alat terlebih dahulu agar dapat menggunakan fitur ini.
        </p>
        <a href="{{ route('client.pengajuan-peminjaman.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-lg me-2"></i>Ajukan Peminjaman Alat
        </a>
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
                                data-bs-target="#modalPenggunaan" data-basename="{{ $baseName }}">Gunakan Alat</button>
                            <button class="btn btn-secondary w-100" data-bs-toggle="modal"
                                data-bs-target="#modalDetail" data-basename="{{ $baseName }}">Detail</button>
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
                        <h5 class="modal-title" id="modalPenggunaanLabel">Gunakan Alat</h5>
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
                        <div class="mb-3">
                            <label class="form-label">Tanggal Penggunaan<span class="text-danger">*</span></label>
                            <input type="text" name="tanggal_penggunaan" id="tanggalPenggunaanInput" class="form-control"
                                required placeholder="Pilih tanggal">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Waktu Mulai<span class="text-danger">*</span></label>
                                <input type="time" name="waktu_mulai" id="waktuMulaiInput" class="form-control"
                                    required min="08:00" max="17:00">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Waktu Selesai<span class="text-danger">*</span></label>
                                <input type="time" name="waktu_selesai" id="waktuSelesaiInput" class="form-control"
                                    required min="08:00" max="17:00">
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



    <!-- Modal Penggunaan Aktif -->
    <div class="modal fade" id="modalPenggunaanAktif" tabindex="-1" aria-labelledby="modalPenggunaanAktifLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPenggunaanAktifLabel">
                        <i class="bi bi-clock-history text-info me-2"></i> Penggunaan Aktif Anda
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 p-3 bg-light rounded border d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-info fs-3 me-3"></i>
                        <div>
                            <div class="fw-bold mb-1">Status Penggunaan Alat</div>
                            <div class="text-muted small">Daftar semua alat yang sedang Anda gunakan. Anda dapat melihat detail penggunaan dan mengembalikan alat yang sudah selesai digunakan.</div>
                        </div>
                    </div>
                    <div id="penggunaanAktifContent"></div>
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
            // === Flatpickr Init untuk Tanggal ===
            const today = new Date();
            const maxDate = new Date();
            maxDate.setDate(today.getDate() + 7); // Maksimal 1 minggu ke depan

            flatpickr('#tanggalPenggunaanInput', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                maxDate: maxDate,
                locale: {
                    firstDayOfWeek: 1, // Senin sebagai hari pertama
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                    }
                }
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
                document.getElementById('tanggalPenggunaanInput').value = '';
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
                const tanggal = document.getElementById('tanggalPenggunaanInput').value;
                const mulai = document.getElementById('waktuMulaiInput').value;
                const selesai = document.getElementById('waktuSelesaiInput').value;
                const qty = parseInt(document.getElementById('qtyInput').value);
                const errorDiv = document.getElementById('errorMessage');
                const alatIdContainer = document.getElementById('alatIdContainer');

                // Reset error
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';

                if (!tujuan || !tanggal || !mulai || !selesai || !qty) {
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

                // Validasi waktu (08:00 - 17:00)
                if (mulai < '08:00' || mulai > '17:00' || selesai < '08:00' || selesai > '17:00') {
                    errorDiv.textContent = 'Waktu penggunaan harus antara 08:00 - 17:00.';
                    errorDiv.style.display = 'block';
                    e.preventDefault();
                    return;
                }

                if (selesai <= mulai) {
                    errorDiv.textContent = 'Waktu selesai harus lebih besar dari waktu mulai.';
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

                // === Set hidden input untuk datetime gabungan (kompatibilitas backend) ===
                const waktuMulaiGabungan = document.createElement('input');
                waktuMulaiGabungan.type = 'hidden';
                waktuMulaiGabungan.name = 'waktu_mulai_datetime';
                waktuMulaiGabungan.value = tanggal + ' ' + mulai;
                alatIdContainer.appendChild(waktuMulaiGabungan);

                const waktuSelesaiGabungan = document.createElement('input');
                waktuSelesaiGabungan.type = 'hidden';
                waktuSelesaiGabungan.name = 'waktu_selesai_datetime';
                waktuSelesaiGabungan.value = tanggal + ' ' + selesai;
                alatIdContainer.appendChild(waktuSelesaiGabungan);
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



            // === Fungsi untuk update statistik cards
            function updateStatisticsCards() {
                const tbody = document.querySelector('#penggunaanAktifContent tbody');
                if (!tbody) return;
                
                // Hitung ulang statistik berdasarkan baris yang tersisa
                const remainingRows = tbody.querySelectorAll('tr:not(.empty-state)');
                const totalAktif = remainingRows.length;
                
                // Hitung berdasarkan badge status yang ada
                let diterima = 0;
                let pending = 0;
                let belumDikembalikan = 0;
                
                remainingRows.forEach(row => {
                    const statusCell = row.querySelector('td:nth-child(4)'); // Kolom status
                    if (statusCell) {
                        const statusText = statusCell.textContent.trim();
                        if (statusText.includes('Diterima')) {
                            diterima++;
                            // Cek apakah ada tombol kembalikan (belum dikembalikan)
                            const actionCell = row.querySelector('td:last-child');
                            if (actionCell && actionCell.querySelector('.btn-kembalikan-alat-aktif')) {
                                belumDikembalikan++;
                            }
                        } else if (statusText.includes('Pending')) {
                            pending++;
                        }
                    }
                });
                
                // Update statistik cards
                const cards = document.querySelectorAll('#penggunaanAktifContent .card h4');
                if (cards.length >= 4) {
                    cards[0].textContent = totalAktif; // Total Aktif
                    cards[1].textContent = diterima; // Diterima
                    cards[2].textContent = pending; // Pending
                    cards[3].textContent = belumDikembalikan; // Belum Dikembalikan
                }
                
                // Jika tidak ada alat aktif, update semua statistik menjadi 0
                if (totalAktif === 0) {
                    if (cards.length >= 4) {
                        cards[0].textContent = '0'; // Total Aktif
                        cards[1].textContent = '0'; // Diterima
                        cards[2].textContent = '0'; // Pending
                        cards[3].textContent = '0'; // Belum Dikembalikan
                    }
                }
            }

            // === Modal Penggunaan Aktif
            document.getElementById('modalPenggunaanAktif').addEventListener('show.bs.modal', function(event) {
                let html = '';
                // Data laporan alat aktif dari blade
                const laporanAlatAktif = @json($laporanAlatAktif);
                
                // Statistik penggunaan aktif
                const totalAktif = laporanAlatAktif.length;
                const diterima = laporanAlatAktif.filter(l => l.status_peminjaman === 'Diterima').length;
                const pending = laporanAlatAktif.filter(l => l.status_peminjaman === 'Pending').length;
                const belumDikembalikan = laporanAlatAktif.filter(l => l.status_peminjaman === 'Diterima' && l.status_pengembalian === 'Belum Dikembalikan').length;
                
                html += `<div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-clock-history fs-2 mb-2"></i>
                                <h4>${totalAktif}</h4>
                                <p class="mb-0">Total Aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-check-circle fs-2 mb-2"></i>
                                <h4>${diterima}</h4>
                                <p class="mb-0">Diterima</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-hourglass-split fs-2 mb-2"></i>
                                <h4>${pending}</h4>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-arrow-return-left fs-2 mb-2"></i>
                                <h4>${belumDikembalikan}</h4>
                                <p class="mb-0">Belum Dikembalikan</p>
                            </div>
                        </div>
                    </div>
                </div>`;
                
                html += `<div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Alat</th>
                                <th>Nomor Seri</th>
                                <th>Kondisi</th>
                                <th>Status Peminjaman</th>
                                <th>Tanggal Pinjam</th>
                                <th>Estimasi Kembali</th>
                                <th>Lokasi</th>
                                <th>Tujuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>`;
                
                if (laporanAlatAktif.length === 0) {
                    html += `<tr class="empty-state"><td colspan='9' class='text-center text-muted py-5'>
                        <div class='mb-3'><i class='bi bi-clock text-info' style='font-size:3rem;'></i></div>
                        <div class='fw-bold'>Tidak ada penggunaan aktif</div>
                        <div class='text-muted small mt-1'>Mulai gunakan alat untuk melihat status di sini</div>
                    </td></tr>`;
                } else {
                    laporanAlatAktif.forEach(laporan => {
                        const alat = laporan.alat || {};
                        // Badge kondisi
                        let badgeKondisi = '';
                        if (alat.condition === 'Baik') badgeKondisi = '<span class="badge bg-success">Baik</span>';
                        else if (alat.condition === 'Rusak') badgeKondisi = '<span class="badge bg-danger">Rusak</span>';
                        else badgeKondisi = `<span class='badge bg-secondary'>${alat.condition || '-'}</span>`;
                        
                        // Badge status peminjaman
                        let badgeStatus = '';
                        if (laporan.status_peminjaman === 'Diterima') badgeStatus = '<span class="badge bg-success">Diterima</span>';
                        else if (laporan.status_peminjaman === 'Pending') badgeStatus = '<span class="badge bg-warning">Pending</span>';
                        else if (laporan.status_peminjaman === 'Ditolak') badgeStatus = '<span class="badge bg-danger">Ditolak</span>';
                        else badgeStatus = `<span class='badge bg-secondary'>${laporan.status_peminjaman || '-'}</span>`;
                        
                        // Nomor seri hanya jika status Diterima
                        let serialNumber = (laporan.status_peminjaman === 'Diterima') ? (alat.serial_number || '-') : '-';
                        
                        // Format tanggal
                        const waktuMulai = laporan.waktu_mulai ? new Date(laporan.waktu_mulai).toLocaleString('id-ID') : '-';
                        const waktuSelesai = laporan.waktu_selesai ? new Date(laporan.waktu_selesai).toLocaleString('id-ID') : '-';
                        
                        // Potong tujuan jika terlalu panjang
                        const tujuan = laporan.tujuan_penggunaan ? 
                            (laporan.tujuan_penggunaan.length > 50 ? 
                                laporan.tujuan_penggunaan.substring(0, 47) + '...' : 
                                laporan.tujuan_penggunaan) : '-';
                        
                        html += `<tr>
                            <td class="fw-semibold">${alat.name || '-'}</td>
                            <td>${serialNumber}</td>
                            <td class="text-center">${badgeKondisi}</td>
                            <td class="text-center">${badgeStatus}</td>
                            <td class="text-muted small">${waktuMulai}</td>
                            <td class="text-muted small">${waktuSelesai}</td>
                            <td>${alat.ruangan?.name || '-'}</td>
                            <td><span title="${laporan.tujuan_penggunaan || ''}">${tujuan}</span></td>
                            <td class="text-center">`;
                        
                        // Tombol kembalikan hanya muncul jika status Diterima dan belum dikembalikan
                        if (laporan.status_peminjaman === 'Diterima' && laporan.status_pengembalian === 'Belum Dikembalikan') {
                            html += `<button class='btn btn-sm btn-success btn-kembalikan-alat-aktif' data-alatid='${alat.id}'>
                                <i class='bi bi-arrow-return-left me-1'></i>Kembalikan
                            </button>`;
                        } else if (laporan.status_pengembalian === 'Dikembalikan') {
                            html += `<span class='badge bg-success'>
                                <i class='bi bi-check-circle me-1'></i>Dikembalikan
                            </span>`;
                        } else {
                            html += `<span class='text-muted'>-</span>`;
                        }
                        
                        html += `</td></tr>`;
                    });
                }
                html += `</tbody></table></div>`;
                document.getElementById('penggunaanAktifContent').innerHTML = html;
            });

            // === Proses Kembalikan Alat dari Modal Aktif (AJAX)
            document.getElementById('penggunaanAktifContent').addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-kembalikan-alat-aktif')) {
                    const alatId = e.target.getAttribute('data-alatid');
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Yakin ingin mengembalikan alat ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Kembalikan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/client/penggunaan-alat/kembalikan/${alatId}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                    },
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: 'Alat berhasil dikembalikan dan menunggu validasi admin!',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                        // Hapus baris alat dari tabel
                                        const btn = e.target;
                                        const row = btn.closest('tr');
                                        row.parentNode.removeChild(row);
                                        
                                        // Jika tidak ada baris alat tersisa (selain header), tampilkan pesan semua alat sudah dikembalikan
                                        const tbody = document.querySelector('#penggunaanAktifContent tbody');
                                        if (tbody && tbody.children.length === 0) {
                                            tbody.innerHTML = `<tr class="empty-state"><td colspan='9' class='text-center text-muted py-5'>
                                                <div class='mb-3'><i class='bi bi-patch-check-fill text-success' style='font-size:3rem;'></i></div>
                                                <div class='fw-bold'>Semua alat sudah dikembalikan!</div>
                                                <div class='text-muted small mt-1'>Tidak ada penggunaan aktif saat ini</div>
                                            </td></tr>`;
                                        }
                                        
                                        // Update statistik cards
                                        updateStatisticsCards();
                                    } else {
                                        Swal.fire('Gagal', data.message || 'Gagal mengembalikan alat.', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error', 'Terjadi kesalahan saat mengembalikan alat.', 'error'));
                        }
                    });
                }
            });


        });
    </script>


</x-admin-layout>
