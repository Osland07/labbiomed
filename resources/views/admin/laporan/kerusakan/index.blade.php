<x-admin-table>
    <x-slot name="title">
        Laporan Kerusakan
    </x-slot>
    <x-slot name="search">
        <form method="GET" class="row gx-2 gy-1 align-items-end flex-wrap mb-2">
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <label class="form-label mb-1" style="font-size:0.95em;">Pengguna</label>
                <select name="filter_user" class="form-select tomselect w-100">
                    <option value="">Semua</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('filter_user') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <label class="form-label mb-1" style="font-size:0.95em;">Alat/Bahan/Ruangan</label>
                <select name="filter_item" class="form-select tomselect w-100">
                    <option value="">Semua</option>
                    @foreach ($items as $item)
                        <option value="{{ $item }}" {{ request('filter_item') == $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <label class="form-label mb-1" style="font-size:0.95em;">Status Penggantian</label>
                <select name="filter_status" class="form-select w-100">
                    @foreach ($statusOptions as $key => $label)
                        <option value="{{ $key }}" {{ request('filter_status', 'all') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" style="height:38px;">Filter</button>
            </div>
        </form>
    </x-slot>
    <x-slot name="export">
        @include('admin.laporan.kerusakan.export')
    </x-slot>
    <!-- Table (scroll horizontal) -->
    <div style="overflow-x:auto;">
        <table id="" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('No') }}</th>
                    <th class="sticky-column bg-white" style="left:0; z-index:2;">{{ __('Pengguna') }}</th>
                    <th>{{ __('Nama Alat/Bahan/Ruangan') }}</th>
                    <th>{{ __('Nomor Seri') }}</th>
                    <th>{{ __('Tanggal Kerusakan') }}</th>
                    <th>{{ __('Deskripsi Kerusakan') }}</th>
                    <th>{{ __('Gambar Kerusakan') }}</th>
                    <th>{{ __('Status Penggantian') }}</th>
                    <th>{{ __('Tanggal Penggantian') }}</th>
                    <th>{{ __('Validator') }}</th>
                    <th>{{ __('Aksi') }}</th>
                    <th>{{ __('Catatan Penggantian') }}</th>
                    <th>{{ __('Bukti Penggantian') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laporans as $laporan)
                    <tr>
                        <td>{{ $laporans->firstItem() + $loop->index }}</td>
                        <td class="sticky-column bg-white" style="left:0; z-index:1; max-width:220px; word-wrap:break-word; white-space:normal;">
                            <span>{{ $laporan->user->name ?? '-' }}</span>
                            / {{ $laporan->user->nim ?? '-' }} /
                            <a href="https://wa.me/+62{{ $laporan->user->no_hp ?? '-' }}">
                                {{ $laporan->user->no_hp ?? '-' }}
                                <i class="fa fa-whatsapp text-success"></i></a> / <a
                                href="mailto:{{ $laporan->user->email ?? '-' }}">{{ $laporan->user->email ?? '-' }} <i
                                    class="fa fa-envelope text-primary"></i></a>
                        </td>
                        <td>{{ $laporan->alat->name ?? ($laporan->bahan->name ?? ($laporan->ruangan->name ?? '-')) }}</td>
                        <td>{{ $laporan->alat->serial_number ?? ($laporan->bahan->serial_number ?? ($laporan->ruangan->serial_number ?? '-')) }}
                        </td>
                        <td>{{ $laporan->tgl_kerusakan ?? '-' }}</td>
                        <td>{{ $laporan->deskripsi_kerusakan ?? '-' }}</td>
                        <td>
                            @if ($laporan->gambar_setelah)
                                <a href="{{ asset('storage/' . $laporan->gambar_setelah) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $laporan->gambar_setelah) }}" width="100">
                                </a>
                            @else
                                <img src="{{ asset('assets/img/default.png') }}" alt="Default" width="100">
                            @endif
                        </td>
                        <td>
                            @if ($laporan->is_replaced)
                                <span class="badge bg-success">Sudah Diganti</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum Diganti</span>
                            @endif
                        </td>
                        <td>{{ $laporan->replaced_at ? date('d-m-Y', strtotime($laporan->replaced_at)) : '-' }}</td>
                        <td>{{ $laporan->replaced_by ? optional(\App\Models\User::find($laporan->replaced_by))->name : '-' }}</td>
                        <td>
                            @if (!$laporan->is_replaced)
                                <!-- Tombol untuk memunculkan modal -->
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiReplace{{ $laporan->id }}">
                                    Konfirmasi Penggantian
                                </button>
                                <!-- Modal Konfirmasi Penggantian -->
                                <div class="modal fade" id="modalKonfirmasiReplace{{ $laporan->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabelReplace{{ $laporan->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.laporan.kerusakan.replace', $laporan->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabelReplace{{ $laporan->id }}">Konfirmasi Penggantian/Perbaikan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="replace_note{{ $laporan->id }}" class="form-label">Catatan (Opsional)</label>
                                                        <textarea name="replace_note" id="replace_note{{ $laporan->id }}" class="form-control" rows="2" placeholder="Catatan penggantian/perbaikan..."></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="replace_image{{ $laporan->id }}" class="form-label">Upload Bukti (Opsional)</label>
                                                        <input type="file" name="replace_image" id="replace_image{{ $laporan->id }}" class="form-control" accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-success">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($laporan->is_replaced && $laporan->replace_note)
                                <span>{{ $laporan->replace_note }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($laporan->is_replaced && $laporan->replace_image)
                                <a href="{{ asset('storage/' . $laporan->replace_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $laporan->replace_image) }}" width="100">
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">Data tidak ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $laporans->appends(['perPage' => $perPage, 'search' => $search])->links() }}
</x-admin-table>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new TomSelect('select[name=filter_user]', {create: false, allowEmptyOption: true, placeholder: 'Cari pengguna...'});
    new TomSelect('select[name=filter_item]', {create: false, allowEmptyOption: true, placeholder: 'Cari item...'});
});
</script>

<style>
.sticky-column {
    position: sticky;
    left: 0;
    background: #fff;
    z-index: 2;
}
</style>
