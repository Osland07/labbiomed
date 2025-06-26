<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        User
    </x-slot>

    <!-- Button Form Create -->
    <x-slot name="formCreate">
        @can('create-user')
            @include('admin.user.create')
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
                <th class="d-none d-lg-table-cell">{{ __('Nomor Induk') }}</th>
                <th class="d-none d-lg-table-cell">{{ __('Level') }}</th>
                <th class="d-none d-lg-table-cell">{{ __('Foto') }}</th>
                <th class="d-none d-lg-table-cell">{{ __('Status') }}</th>
                <th class="text-center">{{ __('Aksi') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users->where('email', '!=', 'super@admin.com') as $user)
                <tr>
                    <td>{{ $users->firstItem() + $loop->index }}</td>
                    <td>{{ $user->name ?? '-' }}</td>
                    <td class="d-none d-lg-table-cell">{{ $user->nim ?? '-' }}</td>
                    <td class="d-none d-lg-table-cell">
                        {{ $user->getRoleNames()->implode(', ') }}
                    </td>
                    <td>
                        @if ($user->img == null)
                            <img src="{{ asset('assets/profile/default.png') }}" alt="{{ $user->name }}"
                                width="100">
                        @else
                            <a href="#" data-bs-toggle="modal" data-bs-target="#myModal{{ $user->id }}">
                                <img class="img img-fluid rounded" src="{{ asset('storage/' . $user->img) }}"
                                    alt="{{ $user->img }}" width="100" loading="lazy">
                            </a>

                            <!-- Modal -->
                            <div class="modal fade" id="myModal{{ $user->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">{{ $user->name }}</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="maximize"><i
                                                                class="fas fa-expand"></i></button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <img class="img img-fluid col-12"
                                                        src="{{ asset('storage/' . $user->img) }}"
                                                        alt="{{ $user->img }}">
                                                    <!-- Tombol Download -->
                                                    <a href="{{ asset('storage/' . $user->img) }}"
                                                        download="{{ $user->img }}"
                                                        class="btn btn-success mt-2 col-12">Download
                                                        Gambar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell">
                        @if ($user->status == 'aktif')
                            <span class="badge badge-success">{{ $user->status }}</span>
                        @elseif ($user->status != 'aktif')
                            <span class="badge badge-danger">{{ $user->status }}</span>
                        @endif
                    </td>
                    <td class="manage-row text-center">
                        @can('edit-user')
                            @include('admin.user.edit')
                        @endcan
                        @can('delete-user')
                            @include('admin.user.delete')
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Data tidak ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $users->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
