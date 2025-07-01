<x-admin-layout>
    <!-- Title -->
    <x-slot name="title">
        Penggunaan Bahan
    </x-slot>

    @include('components.alert')

    <!-- Form Ajukan Penggunaan Bahan -->
    <form id="penggunaanBahanForm" class="bg-white p-4 rounded shadow mb-4 pb-4" method="POST" action="{{ route('client.penggunaan-bahan.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Bahan<span class="text-danger">*</span></label>
            <select name="bahan_id" class="form-select" id="bahan_id_pengajuan" required onchange="updateJumlahInputPengajuan()">
                <option value="" disabled selected>Pilih Bahan</option>
                @foreach ($bahans as $bahan)
                    <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->unit }}">{{ $bahan->name }} (Stok: {{ $bahan->stock }} {{ $bahan->unit }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Jumlah<span class="text-danger">*</span></label>
            <input type="number" name="jumlah" class="form-control" id="jumlah_pengajuan" min="1" required placeholder="Jumlah penggunaan">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Tujuan Penggunaan<span class="text-danger">*</span></label>
            <input name="tujuan" type="text" class="form-control" required value="{{ old('tujuan') }}" placeholder="Tujuan penggunaan bahan">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan tambahan"></textarea>
        </div>
        <div class="text-center">
            <button class="btn btn-primary px-5 py-2" type="submit">Ajukan Penggunaan</button>
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
                                        <span class="badge bg-warning text-dark">Pending</span>
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
function updateJumlahInputPengajuan() {
    var select = document.getElementById('bahan_id_pengajuan');
    var satuan = select.options[select.selectedIndex].getAttribute('data-satuan') || '';
    var jumlahInput = document.getElementById('jumlah_pengajuan');
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
</script> 