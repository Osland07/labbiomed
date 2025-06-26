<!-- Tombol untuk membuka modal -->
<button role="button" class="btn btn-sm m-1 btn-warning" data-bs-toggle="modal"
    data-bs-target=".formEdit{{ $bahan->id }}"><i class="fas fa-edit"></i></button>

<!-- Modal -->
<div class="modal fade formEdit{{ $bahan->id }}" tabindex="-1" role="dialog" aria-hidden="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.bahan.update', $bahan->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">{{ __('Edit Data') }}
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Nama') }}<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Masukkan Nama Bahan" value="{{ old('name', $bahan->name) }}" required>
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
                                    {{ old('category_id', $bahan->category_id) == $category->id ? 'selected' : '' }}>
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
                            placeholder="Masukkan Lokasi Bahan" value="{{ old('location', $bahan->location) }}"
                            required>
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
                                $selectedUnit = old('unit') ?? ($bahan->unit ?? '');
                            @endphp
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}" {{ $selectedUnit == $unit ? 'selected' : '' }}>
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
                            value="{{ old('stock', $bahan->stock) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-4">
                        <label class="form-label">{{ __('Stok Minimal') }}<span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" min="0"
                            class="form-control @error('min_stock') is-invalid @enderror" placeholder="10"
                            value="{{ old('min_stock', $bahan->min_stock) }}" required>
                        @error('min_stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Tanggal Diterima') }}<span
                                class="text-danger">*</span></label>
                        <input type="date" name="date_received"
                            class="form-control @error('date_received') is-invalid @enderror"
                            value="{{ old('date_received', $bahan->date_received) }}" required>
                        @error('date_received')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Tanggal Kadaluarsa') }}<span
                                class="text-danger">*</span></label>
                        <input type="date" name="date_expired"
                            class="form-control @error('date_expired') is-invalid @enderror"
                            value="{{ old('date_expired', $bahan->date_expired) }}" required>
                        @error('date_expired')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ __('Catatan') }}</label>
                        <textarea name="desc" class="form-control @error('desc') is-invalid @enderror" rows="1"
                            placeholder="Masukkan Catatan">{{ old('desc', $bahan->desc) }}</textarea>
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
