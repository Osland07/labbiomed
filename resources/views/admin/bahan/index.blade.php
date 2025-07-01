<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Bahan
    </x-slot>

    <!-- Button Form Create -->
    <x-slot name="formCreate">
        @can('create-bahan')
            @include('admin.bahan.create')
        @endcan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    @if ($stokRendah->count())
        <div class="alert border border-warning bg-warning bg-opacity-10 text-dark">
            <div class="mb-1">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Peringatan Stok Rendah</strong>
            </div>
            <div>
                {{ $stokRendah->count() }} item memiliki stok di bawah batas minimum:
            </div>
            <div class="mt-2 d-flex flex-wrap gap-2">
                @foreach ($stokRendah as $item)
                    <span class="badge bg-danger rounded-pill">
                        {{ $item->name }} ({{ $item->stock }} {{ $item->unit }})
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Tombol di atas tabel -->
    <div class="d-flex mb-3">
        @can('create-bahan')
            @include('admin.bahan.create')
        @endcan
        <button type="button" class="btn btn-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalBahanMasuk">
            <i class="fas fa-plus-circle"></i> Bahan Masuk
        </button>
        <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalBahanKeluar">
            <i class="fas fa-minus-circle"></i> Bahan Keluar
        </button>
    </div>

    <!-- Modal Bahan Masuk -->
    <div class="modal fade" id="modalBahanMasuk" tabindex="-1" aria-labelledby="modalBahanMasukLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('admin.bahan.masuk') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="modalBahanMasukLabel">Bahan Masuk</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="bahan_id_masuk" class="form-label">Pilih Bahan</label>
                <select name="bahan_id" id="bahan_id_masuk" class="form-select select2" required onchange="updateSatuanMasuk(); updateStokMasuk();">
                  <option value="" data-satuan="" data-stok="">-- Pilih Bahan --</option>
                  @foreach($allBahans as $bahan)
                    <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->unit }}" data-stok="{{ $bahan->stock }}">{{ $bahan->name }} (Stok: {{ $bahan->stock }} {{ $bahan->unit }})</option>
                  @endforeach
                </select>
                <div class="form-text text-primary" id="stok-masuk-info"></div>
              </div>
              <div class="mb-3">
                <label for="jumlah_masuk" class="form-label">Jumlah Masuk <span id="satuan-masuk" class="text-muted"></span></label>
                <input type="number" name="jumlah" id="jumlah_masuk" class="form-control" min="0.01" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="keterangan_masuk" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan_masuk" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-success">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Bahan Keluar -->
    <div class="modal fade" id="modalBahanKeluar" tabindex="-1" aria-labelledby="modalBahanKeluarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('admin.bahan.keluar') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="modalBahanKeluarLabel">Bahan Keluar</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="bahan_id_keluar" class="form-label">Pilih Bahan</label>
                <select name="bahan_id" id="bahan_id_keluar" class="form-select select2" required onchange="updateSatuanKeluar(); updateStokKeluar();">
                  <option value="" data-satuan="" data-stok="">-- Pilih Bahan --</option>
                  @foreach($allBahans as $bahan)
                    <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->unit }}" data-stok="{{ $bahan->stock }}">{{ $bahan->name }} (Stok: {{ $bahan->stock }} {{ $bahan->unit }})</option>
                  @endforeach
                </select>
                <div class="form-text text-primary" id="stok-keluar-info"></div>
              </div>
              <div class="mb-3">
                <label for="jumlah_keluar" class="form-label">Jumlah Keluar <span id="satuan-keluar" class="text-muted"></span></label>
                <input type="number" name="jumlah" id="jumlah_keluar" class="form-control" min="0.01" step="0.01" required>
              </div>
              <div class="mb-3">
                <label for="keterangan_keluar" class="form-label">Keterangan</label>
                <textarea name="keterangan" id="keterangan_keluar" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-danger">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Table -->
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th>{{ __('Nama') }}</th>
                <th>{{ __('Kategori') }}</th>
                <th>{{ __('Stock') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Lokasi') }}</th>
                <th>{{ __('Tanggal Diterima') }}</th>
                <th class="text-center">{{ __('Aksi') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bahans as $bahan)
                <tr>
                    <td>{{ $bahans->firstItem() + $loop->index }}</td>
                    <td>{{ $bahan->name ?? '-' }}</td>
                    <td>{{ $bahan->category->name ?? '-' }}</td>
                    <td>
                        <p class="p-0 m-0">{{ $bahan->stock ?? '0' }} {{ $bahan->unit ?? '-' }}</p>
                        <p class="text-muted small p-0 m-0">Min Stok: {{ $bahan->min_stock ?? '0' }}
                            {{ $bahan->unit ?? '-' }}</p>
                    </td>
                    <td>
                        @if ($bahan->stock <= $bahan->min_stock)
                            <span class="badge badge-danger">Stok Rendah</span>
                        @else
                            <span class="badge badge-primary">Stok Cukup</span>
                        @endif
                    </td>
                    <td>{{ $bahan->location ?? '-' }}</td>
                    <td>{{ $bahan->date_received ?? '-' }}</td>
                    <td class="manage-row text-center">
                        @include('admin.bahan.detail')
                        @can('edit-bahan')
                            @include('admin.bahan.edit')
                        @endcan
                        @can('delete-bahan')
                            @include('admin.bahan.delete')
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $bahans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

    <!-- Tabel Riwayat Transaksi Bahan -->
    <div class="card mt-4">
        <div class="card-header bg-light py-2">
            <h6 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi Bahan</h6>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Bahan</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $logs = \App\Models\BahanLog::with(['bahan', 'user'])->latest()->limit(30)->get();
                        @endphp
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $log->bahan->name ?? '-' }}</td>
                                <td>
                                    @if($log->tipe == 'masuk')
                                        <span class="badge bg-success">Masuk</span>
                                    @else
                                        <span class="badge bg-danger">Keluar</span>
                                    @endif
                                </td>
                                <td>{{ $log->jumlah }}</td>
                                <td>{{ $log->bahan->unit ?? '-' }}</td>
                                <td>{{ $log->keterangan ?? '-' }}</td>
                                <td>{{ $log->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada transaksi bahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tom Select CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
    function updateJumlahInputMasuk() {
        var select = document.getElementById('bahan_id_masuk');
        var satuan = select.options[select.selectedIndex].getAttribute('data-satuan') || '';
        var jumlahInput = document.getElementById('jumlah_masuk');
        if (satuan.toLowerCase() === 'pcs' || satuan.toLowerCase() === 'unit') {
            jumlahInput.step = 1;
            jumlahInput.min = 1;
            jumlahInput.value = Math.max(1, parseInt(jumlahInput.value) || 1);
        } else {
            jumlahInput.step = 0.01;
            jumlahInput.min = 0.01;
            jumlahInput.value = Math.max(0.01, parseFloat(jumlahInput.value) || 0.01);
        }
    }
    function updateJumlahInputKeluar() {
        var select = document.getElementById('bahan_id_keluar');
        var satuan = select.options[select.selectedIndex].getAttribute('data-satuan') || '';
        var jumlahInput = document.getElementById('jumlah_keluar');
        if (satuan.toLowerCase() === 'pcs' || satuan.toLowerCase() === 'unit') {
            jumlahInput.step = 1;
            jumlahInput.min = 1;
            jumlahInput.value = Math.max(1, parseInt(jumlahInput.value) || 1);
        } else {
            jumlahInput.step = 0.01;
            jumlahInput.min = 0.01;
            jumlahInput.value = Math.max(0.01, parseFloat(jumlahInput.value) || 0.01);
        }
    }
    function updateSatuanMasuk() {
        var select = document.getElementById('bahan_id_masuk');
        var satuan = select.options[select.selectedIndex].getAttribute('data-satuan') || '';
        document.getElementById('satuan-masuk').textContent = satuan ? '(' + satuan + ')' : '';
        updateJumlahInputMasuk();
    }
    function updateSatuanKeluar() {
        var select = document.getElementById('bahan_id_keluar');
        var satuan = select.options[select.selectedIndex].getAttribute('data-satuan') || '';
        document.getElementById('satuan-keluar').textContent = satuan ? '(' + satuan + ')' : '';
        updateJumlahInputKeluar();
    }
    function updateStokMasuk() {
        var select = document.getElementById('bahan_id_masuk');
        var stok = select.options[select.selectedIndex].getAttribute('data-stok') || '';
        document.getElementById('stok-masuk-info').textContent = stok ? 'Stok tersedia: ' + stok : '';
    }
    function updateStokKeluar() {
        var select = document.getElementById('bahan_id_keluar');
        var stok = select.options[select.selectedIndex].getAttribute('data-stok') || '';
        document.getElementById('stok-keluar-info').textContent = stok ? 'Stok tersedia: ' + stok : '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#bahan_id_masuk', {
            placeholder: 'Cari bahan...',
            allowEmptyOption: true,
            maxOptions: 100,
            onDropdownOpen: function() { updateSatuanMasuk(); updateStokMasuk(); }
        });
        new TomSelect('#bahan_id_keluar', {
            placeholder: 'Cari bahan...',
            allowEmptyOption: true,
            maxOptions: 100,
            onDropdownOpen: function() { updateSatuanKeluar(); updateStokKeluar(); }
        });
    });
    </script>

</x-admin-table>
