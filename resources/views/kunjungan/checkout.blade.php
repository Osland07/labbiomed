<x-guest-layout>
     @include('components.alert')
<div class="container mt-5">
    <h2>Check-out Kunjungan dari Ruangan: <b>{{ $ruangan->nama }}</b></h2>
    <form method="POST" action="{{ route('kunjungan.checkout.store', $ruangan->id) }}">
        @csrf
        @if($user)
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" class="form-control" value="{{ $user->name }}" disabled>
            </div>
            <button type="submit" class="btn btn-success">Check-out</button>
        @else
            <div class="mb-3">
                <label>Pilih Nama</label>
                <select name="kunjungan_id" class="form-control" required>
                    <option value="">-- Pilih Nama --</option>
                    @foreach($kunjungans as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->nim_nip }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
</x-guest-layout>