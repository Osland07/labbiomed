<x-admin-table>

    <!-- Title -->
    <x-slot name="title">
        Kunjungan
    </x-slot>

<div class="container mt-4">
    <h2>Riwayat Kunjungan Saya</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ruangan</th>
                            <th>Keperluan</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungans as $k)
                        <tr>
                            <td>{{ $k->ruangan->nama ?? '-' }}</td>
                            <td>{{ $k->tujuan }}</td>
                            <td>{{ $k->waktu_masuk }}</td>
                            <td>{{ $k->waktu_keluar ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada riwayat kunjungan.</td>
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