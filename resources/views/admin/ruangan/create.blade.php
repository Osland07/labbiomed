<!-- Tombol untuk membuka modal -->
<button role="button" class="btn btn-sm m-1 btn-primary" data-bs-toggle="modal" data-bs-target=".formCreate"><i
        class="fas fa-plus"></i><span class="d-none d-sm-inline"> {{ __('Tambah') }}</span></button>

<!-- Modal -->
<div class="modal fade formCreate" tabindex="-1" role="dialog" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.ruangan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormLabel">{{ __('Tambah Data') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left row">
                    <div class="row">
                        <!-- Nama -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Nama') }}<span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                placeholder="Nama ruangan" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kapasitas -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Kapasitas') }}<span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas"
                                class="form-control @error('kapasitas') is-invalid @enderror"
                                value="{{ old('kapasitas') }}" placeholder="100" required>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gedung -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Gedung') }}<span class="text-danger">*</span></label>
                            <input type="text" name="gedung"
                                class="form-control @error('gedung') is-invalid @enderror" value="{{ old('gedung') }}"
                                placeholder="GK1" required>
                            @error('gedung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Lantai -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Lantai') }}<span class="text-danger">*</span></label>
                            <input type="text" name="lantai"
                                class="form-control @error('lantai') is-invalid @enderror" value="{{ old('lantai') }}"
                                placeholder="Lantai 1" required>
                            @error('lantai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto Ruangan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Foto Ruangan') }}</label>
                            <input type="file" name="foto_ruangan"
                                class="form-control @error('foto_ruangan') is-invalid @enderror" accept="image/*">
                            @error('foto_ruangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto Denah -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Foto Denah') }}</label>
                            <input type="file" name="foto_denah"
                                class="form-control @error('foto_denah') is-invalid @enderror" accept="image/*">
                            @error('foto_denah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Keterangan') }}<span class="text-danger">*</span></label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                                placeholder="keterangan ruangan" required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
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
