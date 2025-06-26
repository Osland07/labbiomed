<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Riwayat Pengajuan Peminjaman
    </x-slot>
 @include('components.alert')
<div class="container-fluid">
    <h1 class="mb-4">Daftar Kunjungan Laboratorium</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ruangan</th>
                            <th>Nama</th>
                            <th>NIM/NIP</th>
                            <th>Instansi</th>
                            <th>Tujuan</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungans as $k)
                        <tr>
                            <td>{{ $k->ruangan->nama ?? '-' }}</td>
                            <td>{{ $k->nama }}</td>
                            <td>{{ $k->nim_nip }}</td>
                            <td>{{ $k->instansi }}</td>
                            <td>{{ $k->tujuan }}</td>
                            <td>{{ $k->waktu_masuk }}</td>
                            <td>{{ $k->waktu_keluar ?? '-' }}</td>
                            <td>{{ $k->catatan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data kunjungan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $kunjungans->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-table>