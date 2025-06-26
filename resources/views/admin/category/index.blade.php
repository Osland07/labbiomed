<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Category
    </x-slot>

    <!-- Button Form Create -->
    <x-slot name="formCreate">
        @can('create-category')
            @include('admin.category.create')
        @endcan
    </x-slot>

    <!-- Search & Pagination -->
    <x-slot name="search">
        @include('components.search')
    </x-slot>

    <!-- Table -->
    <table id="" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('No') }}</th>
                <th>{{ __('Nama') }}</th>
                <th>{{ __('Tipe') }}</th>
                <th class="text-center">{{ __('Aksi') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $categories->firstItem() + $loop->index }}</td>
                    <td>{{ $category->name ?? '-' }}</td>
                    <td>
                        @if ($category->type == 'alat')
                            <span class="badge badge-primary">Alat</span>
                        @elseif ($category->type == 'bahan')
                            <span class="badge badge-success">Bahan</span>
                        @else
                            <span class="badge badge-secondary">Ruangan</span>
                        @endif
                    </td>
                    <td class="manage-row text-center">
                        <!-- Edit and Delete Button -->
                        @can('edit-category')
                            @include('admin.category.edit')
                        @endcan
                        @can('delete-category')
                            @include('admin.category.delete')
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $categories->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
