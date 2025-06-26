<!-- View Button -->
<a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target=".viewModal{{ $bahan->id }}">
    <i class="fas fa-eye"></i>
</a>

<!-- View Modal -->
<div class="modal fade viewModal{{ $bahan->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white py-2">
                <h5 class="modal-title">
                    <i class="fas fa-box me-2"></i>Detail Bahan
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
                                        <td width="35%"><strong>Nama Bahan</strong></td>
                                        <td>: {{ $bahan->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Kategori</strong></td>
                                        <td>: {{ $bahan->category->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lokasi</strong></td>
                                        <td>: {{ $bahan->location ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tgl Diterima</strong></td>
                                        <td>: {{ $bahan->date_received ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tgl Kadaluarsa</strong></td>
                                        <td>: {{ $bahan->date_expired ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- Informasi Stok -->
                        <div class="card mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Informasi Stok</h6>
                            </div>
                            <div class="card-body p-2">
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td width="35%"><strong>Stok Saat Ini</strong></td>
                                        <td>: {{ $bahan->stock }} {{ $bahan->unit }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stok Minimal</strong></td>
                                        <td>: {{ $bahan->min_stock }} {{ $bahan->unit }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status Stok</strong></td>
                                        <td>:
                                            @if ($bahan->stock <= $bahan->min_stock)
                                                <span class="badge bg-danger">Stok Rendah</span>
                                            @else
                                                <span class="badge bg-success">Stok Cukup</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Gambar -->
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0"><i class="fas fa-image me-2"></i>Gambar Bahan</h6>
                            </div>
                            <div class="card-body text-center p-2">
                                @if ($bahan->img)
                                    <img src="{{ asset('storage/' . $bahan->img) }}" alt="{{ $bahan->name }}"
                                        class="img-fluid rounded" style="max-height: 300px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $bahan->img) }}" download="{{ $bahan->name }}"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p class="mb-0 small">Tidak ada gambar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Catatan -->
                @if ($bahan->desc)
                    <div class="card mt-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan</h6>
                        </div>
                        <div class="card-body p-2">
                            <p class="mb-0">{{ $bahan->desc }}</p>
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
