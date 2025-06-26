<!-- Tombol untuk membuka modal -->
<button role="button" class="btn btn-sm m-1 btn-warning" data-bs-toggle="modal"
    data-bs-target=".formEdit{{ $alat->id }}"><i class="fas fa-edit"></i></button>

<!-- Modal -->
<div class="modal fade formEdit{{ $alat->id }}" tabindex="-1" role="dialog" aria-hidden="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.alat.update', $alat->id) }}" enctype="multipart/form-data">
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
                    <!-- Nama Alat -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Nama Alat') }}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="edit_name" value="{{ old('name', $alat->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Gambar -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Gambar (Opsional)') }}</label>
                        <input type="file" class="form-control @error('img') is-invalid @enderror" name="img"
                            id="edit_img">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                        @error('img')
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
                                    {{ old('category_id', $alat->category_id) == $category->id ? 'selected' : '' }}>
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
                                    {{ old('location', $alat->location) == $location->id ? 'selected' : '' }}>
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
                            value="{{ old('detail_location', $alat->detail_location) }}" required>
                        @error('detail_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Diterima -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Tanggal Diterima') }}<span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('date_received') is-invalid @enderror"
                            name="date_received" value="{{ old('date_received', $alat->date_received) }}" required>
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
                                    {{ old('source', $alat->source) == $source['id'] ? 'selected' : '' }}>
                                    {{ $source['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Spesifikasi -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Spesifikasi Alat') }}</label>
                        <textarea class="form-control @error('desc') is-invalid @enderror" name="desc" rows="1"
                            placeholder="Masukkan Spesifikasi Alat">{{ old('desc', $alat->desc) }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kondisi -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Kondisi Alat') }}<span class="text-danger">*</span></label>
                        <select name="condition" class="form-select @error('condition') is-invalid @enderror">
                            <option value="Baik"
                                {{ old('condition', $alat->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak"
                                {{ old('condition', $alat->condition) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                        @error('condition')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3 col-md-6">
                        <label class="form-label">{{ __('Status Alat') }}<span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Tersedia"
                                {{ old('status', $alat->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dipinjam"
                                {{ old('status', $alat->status) == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Auto Validasi -->
                    <div class="mb-3 col-md-12">
                        <label class="form-label">{{ __('Auto Validasi') }}</label>
                        <div class="form-check form-switch ms-4">
                            <input class="form-check-input" type="checkbox" id="auto_validate" name="auto_validate"
                                value="1" {{ old('auto_validate', $alat->auto_validate) == 1 ? 'checked' : '' }}>
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
