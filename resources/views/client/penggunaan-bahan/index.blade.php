<x-admin-layout>
    <!-- Title -->
    <x-slot name="title">
        Penggunaan Bahan
    </x-slot>

    <!-- TomSelect CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-wrapper.single .ts-control {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            font-size: 0.875rem;
            min-height: 2.5rem;
        }
        .ts-wrapper.single .ts-control.focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
    </style>

    @include('components.alert')

    <!-- Form Ajukan Penggunaan Bahan -->
    <form id="penggunaanBahanForm" class="bg-white p-4 rounded shadow mb-4 pb-4" method="POST" action="{{ route('client.penggunaan-bahan.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Tujuan Penggunaan<span class="text-danger">*</span></label>
            <input name="tujuan" type="text" class="form-control" required value="{{ old('tujuan') }}" placeholder="Tujuan penggunaan bahan">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan"></textarea>
        </div>
        
        <!-- Daftar Bahan -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label fw-semibold mb-0">Daftar Bahan yang Digunakan<span class="text-danger">*</span></label>
                <button type="button" class="btn btn-primary btn-sm" id="addBahanRow">
                    <i class="fas fa-plus me-1"></i>Tambah Bahan
                </button>
            </div>
            
            <small class="text-muted mb-2 d-block">Minimum 1 bahan harus dipilih</small>
            <div class="table-responsive">
                <table class="table table-bordered" id="bahanTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">Bahan</th>
                            <th width="20%">Jumlah</th>
                            <th width="15%">Satuan</th>
                            <th width="15%">Stok Tersedia</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bahanTableBody">
                        <!-- Row awal akan ditambahkan via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center">
            <button class="btn btn-primary px-5 py-2" type="submit" id="submitBtn">Ajukan Penggunaan</button>
        </div>
    </form>

    <!-- Tabel Riwayat Penggunaan Bahan -->
    <div class="card mt-4">
        <div class="card-header">
            <strong>Riwayat Pengajuan Penggunaan Bahan</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Bahan</th>
                            <th>Jumlah</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->bahan->name ?? '-' }}</td>
                                <td>{{ $item->jumlah }} {{ $item->bahan->unit ?? '' }}</td>
                                <td>{{ $item->tujuan }}</td>
                                <td>
                                    @if($item->status == 'pending')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $item->keterangan }}</td>
                                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pengajuan penggunaan bahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
let rowIndex = 0;
const bahansData = @json($bahans);

document.addEventListener('DOMContentLoaded', function() {
    // Tambah row pertama otomatis
    addBahanRow();
    
    // Event listener untuk tombol tambah bahan
    document.getElementById('addBahanRow').addEventListener('click', addBahanRow);
    
    // Event listener untuk form submission
    document.getElementById('penggunaanBahanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (validateForm()) {
            this.submit();
        }
    });
});

function addBahanRow() {
    const tableBody = document.getElementById('bahanTableBody');
    const row = document.createElement('tr');
    row.id = `bahan-row-${rowIndex}`;
    
    row.innerHTML = `
        <td>
            <select name="bahans[${rowIndex}][bahan_id]" class="form-select bahan-select" required onchange="updateBahanInfo(${rowIndex})">
                <option value="">-- Pilih Bahan --</option>
                ${bahansData.map(bahan => 
                    `<option value="${bahan.id}" data-unit="${bahan.unit}" data-stock="${bahan.stock}">
                        ${bahan.name}
                    </option>`
                ).join('')}
            </select>
        </td>
        <td>
            <input type="number" name="bahans[${rowIndex}][jumlah]" class="form-control jumlah-input" 
                   min="0.01" step="0.01" required placeholder="0" onchange="validateStock(${rowIndex})">
        </td>
        <td>
            <span class="unit-display" id="unit-${rowIndex}">-</span>
        </td>
        <td>
            <span class="stock-display" id="stock-${rowIndex}">-</span>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeBahanRow(${rowIndex})" 
                    ${rowIndex === 0 ? 'style="display:none"' : ''}>
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tableBody.appendChild(row);
    
    // Initialize TomSelect untuk dropdown yang baru ditambahkan
    const selectElement = row.querySelector('.bahan-select');
    new TomSelect(selectElement, {
        placeholder: 'Cari bahan...',
        allowEmptyOption: true,
        maxOptions: 100
    });
    
    rowIndex++;
    updateRemoveButtons();
}

function removeBahanRow(index) {
    const row = document.getElementById(`bahan-row-${index}`);
    if (row) {
        row.remove();
        updateRemoveButtons();
    }
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('#bahanTableBody tr');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.btn-danger');
        if (removeBtn) {
            // Sembunyikan tombol hapus jika hanya ada 1 row
            removeBtn.style.display = rows.length <= 1 ? 'none' : 'inline-block';
        }
    });
}

function updateBahanInfo(index) {
    const select = document.querySelector(`select[name="bahans[${index}][bahan_id]"]`);
    const selectedOption = select.options[select.selectedIndex];
    const unitDisplay = document.getElementById(`unit-${index}`);
    const stockDisplay = document.getElementById(`stock-${index}`);
    const jumlahInput = document.querySelector(`input[name="bahans[${index}][jumlah]"]`);
    
    if (selectedOption && selectedOption.value) {
        const unit = selectedOption.dataset.unit;
        const stock = parseFloat(selectedOption.dataset.stock);
        
        unitDisplay.textContent = unit;
        stockDisplay.textContent = `${stock} ${unit}`;
        
        // Update input attributes based on unit type
        if (unit.toLowerCase() === 'pcs' || unit.toLowerCase() === 'unit') {
            jumlahInput.step = '1';
            jumlahInput.min = '1';
        } else {
            jumlahInput.step = '0.01';
            jumlahInput.min = '0.01';
        }
        
        // Clear previous value
        jumlahInput.value = '';
        jumlahInput.max = stock;
    } else {
        unitDisplay.textContent = '-';
        stockDisplay.textContent = '-';
        jumlahInput.value = '';
        jumlahInput.removeAttribute('max');
    }
}

function validateStock(index) {
    const select = document.querySelector(`select[name="bahans[${index}][bahan_id]"]`);
    const jumlahInput = document.querySelector(`input[name="bahans[${index}][jumlah]"]`);
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        const stock = parseFloat(selectedOption.dataset.stock);
        const jumlah = parseFloat(jumlahInput.value);
        
        if (jumlah > stock) {
            jumlahInput.setCustomValidity(`Jumlah tidak boleh melebihi stok tersedia (${stock})`);
            jumlahInput.classList.add('is-invalid');
        } else {
            jumlahInput.setCustomValidity('');
            jumlahInput.classList.remove('is-invalid');
        }
    }
}

function validateForm() {
    const rows = document.querySelectorAll('#bahanTableBody tr');
    let isValid = true;
    const selectedBahans = new Set();
    
    // Check if at least one row exists
    if (rows.length === 0) {
        alert('Minimal harus ada 1 bahan yang dipilih');
        return false;
    }
    
    // Validate each row
    rows.forEach((row, index) => {
        const bahanSelect = row.querySelector('.bahan-select');
        const jumlahInput = row.querySelector('.jumlah-input');
        
        // Check if bahan is selected
        if (!bahanSelect.value) {
            isValid = false;
            bahanSelect.classList.add('is-invalid');
        } else {
            bahanSelect.classList.remove('is-invalid');
            
            // Check for duplicate bahan
            if (selectedBahans.has(bahanSelect.value)) {
                isValid = false;
                alert('Tidak boleh memilih bahan yang sama lebih dari sekali');
                bahanSelect.classList.add('is-invalid');
                return false;
            }
            selectedBahans.add(bahanSelect.value);
        }
        
        // Check if jumlah is filled
        if (!jumlahInput.value || parseFloat(jumlahInput.value) <= 0) {
            isValid = false;
            jumlahInput.classList.add('is-invalid');
        } else {
            jumlahInput.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        alert('Mohon lengkapi semua field yang diperlukan');
    }
    
    return isValid;
}
</script> 