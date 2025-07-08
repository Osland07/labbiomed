<x-admin-layout>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Ajukan Penggunaan Bahan</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.penggunaan-bahan.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="bahan_id" class="form-label">Pilih Bahan<span class="text-danger">*</span></label>
                            <select name="bahan_id" id="bahan_id" class="form-select @error('bahan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahans as $bahan)
                                    <option value="{{ $bahan->id }}" {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>
                                        {{ $bahan->name }} (Stok: {{ (float)$bahan->stock }} {{ $bahan->unit }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bahan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah<span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" min="0.01" step="0.01" value="{{ old('jumlah') }}" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tujuan" class="form-label">Tujuan Penggunaan</label>
                            <input type="text" name="tujuan" id="tujuan" class="form-control @error('tujuan') is-invalid @enderror" value="{{ old('tujuan') }}">
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="2">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                        <a href="{{ route('client.penggunaan-bahan') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout> 