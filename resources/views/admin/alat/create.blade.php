<!-- Tombol untuk membuka modal -->
<button role="button" class="btn btn-sm m-1 btn-primary" data-bs-toggle="modal" data-bs-target=".formCreate"><i
        class="fas fa-plus"></i><span class="d-none d-sm-inline"> {{ __('Tambah') }}</span></button>

<!-- Modal -->
<div class="modal fade formCreate" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.alat.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">{{ __('Tambah Data') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left row">
                    <!-- Nama Alat -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Nama Alat') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" placeholder="Masukkan Nama Alat" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Jumlah') }}<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="qty" id="edit_qty"
                            placeholder="Masukkan Jumlah Alat" value="{{ old('qty') }}" required>
                        @error('qty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Kategori') }}<span class="text-danger">*</span></label>
                        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id"
                            required>
                            <option value="">{{ __('Pilih Kategori') }}</option>
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

                    <!-- Lokasi -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Lokasi') }}<span class="text-danger">*</span></label>
                        <select name="location" class="form-select @error('location') is-invalid @enderror"
                            id="location">
                            <option value="">{{ __('Pilih Lokasi') }}</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ old('location') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Detail Lokasi -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Detail Lokasi') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('detail_location') is-invalid @enderror"
                            name="detail_location" placeholder="Masukkan Lokasi Alat"
                            value="{{ old('detail_location') }}" required>
                        @error('detail_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Spesifikasi -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Spesifikasi Alat') }}</label>
                        <textarea class="form-control @error('desc') is-invalid @enderror" name="desc" rows="1"
                            placeholder="Masukkan Spesifikasi Alat">{{ old('desc') }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Diterima -->
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

                    <!-- Sumber -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Sumber') }}<span class="text-danger">*</span></label>
                        <select name="source" class="form-select @error('source') is-invalid @enderror" id="source">
                            <option value="">{{ __('Pilih Sumber') }}</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source['id'] }}"
                                    {{ old('source') == $source['id'] ? 'selected' : '' }}>
                                    {{ $source['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gambar -->
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ __('Gambar') }}</label>
                        <input type="file" class="form-control @error('img') is-invalid @enderror" name="img">
                        @error('img')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Auto Validasi -->
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ __('Auto Validasi') }}</label>
                        <div class="form-check form-switch ms-4">
                            <input class="form-check-input" type="checkbox" id="auto_validate" name="auto_validate"
                                value="1">
                            <label class="form-check-label" for="auto_validate">Auto Validasi</label>
                        </div>
                        @error('auto_validate')
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
