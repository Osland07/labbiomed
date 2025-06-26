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

</x-admin-table>
