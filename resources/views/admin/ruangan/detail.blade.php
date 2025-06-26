<!-- View Button -->
<a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target=".viewModal{{ $ruangan->id }}">
    <i class="fas fa-eye"></i>
</a>

<!-- View Modal -->
<div class="modal fade viewModal{{ $ruangan->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title">
                    <i class="fas fa-door-open me-2"></i>Detail Ruangan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row">
                    <!-- Informasi -->
                    <div class="col-md-7">
                        <!-- Informasi Dasar -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                            </div>
                            <div class="card-body p-2">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="35%" class="text-end pe-3"><strong>Nama Ruangan</strong></td>
                                        <td width="5%" class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end pe-3"><strong>Kapasitas</strong></td>
                                        <td class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->kapasitas }} Orang</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end pe-3"><strong>Gedung</strong></td>
                                        <td class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->gedung }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end pe-3"><strong>Lantai</strong></td>
                                        <td class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->lantai }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end pe-3"><strong>Keterangan</strong></td>
                                        <td class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->keterangan ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Status Ruangan -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Status Ruangan</h6>
                            </div>
                            <div class="card-body p-2">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="35%" class="text-end pe-3">
                                            <i class="fas fa-users me-1"></i>
                                            <strong>Kapasitas</strong>
                                        </td>
                                        <td width="5%" class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->kapasitas }} Orang</td>
                                    </tr>
                                    <tr>
                                        <td class="text-end pe-3">
                                            <i class="fas fa-building me-1"></i>
                                            <strong>Lokasi</strong>
                                        </td>
                                        <td class="text-center">:</td>
                                        <td class="text-start ps-2">{{ $ruangan->gedung }} - Lantai
                                            {{ $ruangan->lantai }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Gambar -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-image me-2"></i>Foto Ruangan</h6>
                            </div>
                            <div class="card-body text-center p-2">
                                @if ($ruangan->foto_ruangan)
                                    <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}"
                                        alt="{{ $ruangan->name }}" class="img-fluid rounded"
                                        style="max-height: 300px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $ruangan->foto_ruangan) }}"
                                            download="{{ $ruangan->name }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p class="mb-0 small">Tidak ada foto ruangan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Denah -->
                @if ($ruangan->foto_denah)
                    <div class="card mt-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0"><i class="fas fa-map me-2"></i>Denah Ruangan</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $ruangan->foto_denah) }}"
                                    alt="Denah {{ $ruangan->name }}" class="img-fluid rounded"
                                    style="max-height: 300px;">
                                <div class="mt-2">
                                    <a href="{{ asset('storage/' . $ruangan->foto_denah) }}"
                                        download="Denah_{{ $ruangan->name }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-download me-1"></i> Download Denah
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
