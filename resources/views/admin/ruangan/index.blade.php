<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Ruangan
    </x-slot>

    <!-- Button Form Create -->
    <x-slot name="formCreate">
        @can('create-ruangan')
            @include('admin.ruangan.create')
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
                <th>{{ __('Kapasitas') }}</th>
                <th>{{ __('Gedung') }}</th>
                <th>{{ __('Lantai') }}</th>
                <th>{{ __('Foto Ruangan') }}</th>
                <th>{{ __('Foto Denah') }}</th>
                <th class="text-center">{{ __('Aksi') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ruangans as $ruangan)
                <tr>
                    <td>{{ $ruangans->firstItem() + $loop->index }}</td>
                    <td>{{ $ruangan->name ?? '-' }}</td>
                    <td>{{ $ruangan->kapasitas ?? '-' }}</td>
                    <td>{{ $ruangan->gedung ?? '-' }}</td>
                    <td>{{ $ruangan->lantai ?? '-' }}</td>
                    <td>
                        @if ($ruangan->foto_ruangan == null)
                            <img src="{{ asset('assets/img/default.png') }}" alt="{{ $ruangan->name }}" width="100">
                        @else
                            <a href="#" data-bs-toggle="modal"
                                data-bs-target=".myModalRuangan{{ $ruangan->id }}">
                                <img class="img img-fluid rounded"
                                    src="{{ asset('storage/' . $ruangan->foto_ruangan) }}"
                                    alt="{{ $ruangan->foto_ruangan }}" width="100" loading="lazy">
                            </a>

                            <!-- Modal -->
                            <div class="modal fade myModalRuangan{{ $ruangan->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Foto Ruangan</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="maximize"><i
                                                                class="fas fa-expand"></i></button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <img class="img img-fluid col-12"
                                                        src="{{ asset('storage/' . $ruangan->foto_ruangan) }}"
                                                        alt="{{ $ruangan->foto_ruangan }}">
                                                    <!-- Tombol Download -->
                                                    <a href="{{ asset('storage/' . $ruangan->foto_ruangan) }}"
                                                        download="{{ $ruangan->foto_ruangan }}"
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
                    <td>
                        @if ($ruangan->foto_denah == null)
                            <img src="{{ asset('assets/img/default.png') }}" alt="{{ $ruangan->name }}"
                                width="100">
                        @else
                            <a href="#" data-bs-toggle="modal" data-bs-target=".myModalDenah{{ $ruangan->id }}">
                                <img class="img img-fluid rounded" src="{{ asset('storage/' . $ruangan->foto_denah) }}"
                                    alt="{{ $ruangan->foto_denah }}" width="100" loading="lazy">
                            </a>

                            <!-- Modal -->
                            <div class="modal fade myModalDenah{{ $ruangan->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Foto Denah</h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool"
                                                            data-card-widget="maximize"><i
                                                                class="fas fa-expand"></i></button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <img class="img img-fluid col-12"
                                                        src="{{ asset('storage/' . $ruangan->foto_denah) }}"
                                                        alt="{{ $ruangan->foto_denah }}">
                                                    <!-- Tombol Download -->
                                                    <a href="{{ asset('storage/' . $ruangan->foto_denah) }}"
                                                        download="{{ $ruangan->foto_denah }}"
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
                    <td class="manage-row text-center">
                        @include('admin.ruangan.detail')
                        @can('edit-ruangan')
                            @include('admin.ruangan.edit')
                        @endcan
                        @can('delete-ruangan')
                            @include('admin.ruangan.delete')
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
    {{ $ruangans->appends(['perPage' => $perPage, 'search' => $search])->links() }}

</x-admin-table>
