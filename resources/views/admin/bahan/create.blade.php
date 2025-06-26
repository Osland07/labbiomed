<!-- Tombol untuk membuka modal -->
<button role="button" class="btn btn-sm m-1 btn-primary" data-bs-toggle="modal" data-bs-target=".formCreate"><i
        class="fas fa-plus"></i><span class="d-none d-sm-inline"> {{ __('Tambah') }}</span></button>

<!-- Modal -->
<div class="modal fade formCreate" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.bahan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">{{ __('Tambah Data') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Nama') }}<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Masukkan Nama Bahan" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Kategori') }}<span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                            required>
                            <option value="">{{ __('-- Pilih Kategori --') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Lokasi') }}<span class="text-danger">*</span></label>
                        <input type="text" name="location"
                            class="form-control @error('location') is-invalid @enderror"
                            placeholder="Masukkan Lokasi Bahan" value="{{ old('location') }}" required>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Gambar') }}</label>
                        <input type="file" name="img" class="form-control @error('img') is-invalid @enderror">
                        @error('img')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="unit" class="form-label">{{ __('Satuan') }}<span
                                class="text-danger">*</span></label>
                        <select name="unit" id="unit" class="form-select @error('unit') is-invalid @enderror"
                            required>
                            <option value="">{{ __('-- Pilih Satuan --') }}</option>
                            @php
                                $units = ['Kg', 'Liter', 'Pcs', 'Unit', 'mL', 'gram'];
                            @endphp
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected' : '' }}>
                                    {{ $unit }}</option>
                            @endforeach
                        </select>
                        @error('unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">{{ __('Stok') }}<span class="text-danger">*</span></label>
                        <input type="number" name="stock" min="0"
                            class="form-control @error('stock') is-invalid @enderror" placeholder="100"
                            value="{{ old('stock') }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">{{ __('Stok Minimal') }}<span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" min="0"
                            class="form-control @error('min_stock') is-invalid @enderror" placeholder="10"
                            value="{{ old('min_stock') }}" required>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Tanggal Diterima') }}<span
                                class="text-danger">*</span></label>
                        <input type="date" name="date_received"
                            class="form-control @error('date_received') is-invalid @enderror"
                            value="{{ old('date_received') }}" required>
                        @error('date_received')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Tanggal Kadaluarsa') }}<span
                                class="text-danger">*</span></label>
                        <input type="date" name="date_expired"
                            class="form-control @error('date_expired') is-invalid @enderror"
                            value="{{ old('date_expired') }}" required>
                        @error('date_expired')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ __('Catatan') }}</label>
                        <textarea name="desc" class="form-control @error('desc') is-invalid @enderror" rows="1"
                            placeholder="Masukkan Catatan">{{ old('desc') }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Tutup') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
