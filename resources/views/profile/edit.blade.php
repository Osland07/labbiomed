<x-admin-layout>

    <!-- Title -->
    <x-slot name="title">
        Profile
    </x-slot>

    @include('components.alert')

    <div class="card mb-5">
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-2">
                            @if ($user->img)
                                <img width="250" class="img img-fluid" src="{{ asset('storage/' . $user->img) }}"
                                    alt="">
                            @else
                                <img width="250" class="img img-fluid"
                                    src="{{ asset('assets/profile/default.png') }}" alt="">
                            @endif
                            <input type="file" class="mt-3 form-control @error('img') is-invalid @enderror" name="img"
                                id="img" accept="image/*">
                            @error('img')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label class="form-label">{{ __('Nama') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="nama" name="name" id="name"
                                        value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">{{ __('No HP') }}</label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                        placeholder="+628123456789" name="no_hp" id="no_hp"
                                        value="{{ old('no_hp', $user->no_hp) }}">
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">{{ __('Nomor Induk') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nim') is-invalid @enderror"
                                        placeholder="123456789" name="nim" id="nim"
                                        value="{{ old('nim', $user->nim) }}" required>
                                    @error('nim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">{{ __('Tahun Masuk') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('angkatan') is-invalid @enderror"
                                        placeholder="2024" name="angkatan" id="angkatan"
                                        value="{{ old('angkatan', $user->angkatan) }}" required>
                                    @error('angkatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label class="form-label">{{ __('Prodi') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="prodi" class="form-select @error('prodi') is-invalid @enderror"
                                        id="prodi">
                                        <option value="">--- Pilih Prodi ---</option>
                                        <option value="Teknik Biomedis"
                                            {{ $user->prodi == 'Teknik Biomedis' ? 'selected' : '' }}>
                                            Teknik Biomedis</option>
                                    </select>
                                    @error('prodi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Email') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="name@gmail.com" name="email" id="email"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Password (Opsional) ') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="password" name="password" id="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
