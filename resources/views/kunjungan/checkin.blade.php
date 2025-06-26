<x-guest-layout>
    @include('components.alert')
<div class="container mt-5">
    <h2>Check-in Kunjungan ke Ruangan: <b>{{ $ruangan->nama }}</b></h2>
    <form method="POST" action="{{ route('kunjungan.checkin.store', $ruangan->id) }}">
        @csrf
        @if($user)
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
            </div>
            <div class="mb-3">
                <label>Keperluan</label>
                <input type="text" name="tujuan" class="form-control" required>
            </div>
        @else
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>NIM/NIP</label>
                <input type="text" name="nim_nip" class="form-control">
            </div>
            <div class="mb-3">
                <label>Instansi</label>
                <input type="text" name="instansi" class="form-control">
            </div>
            <div class="mb-3">
                <label>Keperluan</label>
                <input type="text" name="tujuan" class="form-control" required>
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Check-in</button>
    </form>
</div>
</x-guest-layout>