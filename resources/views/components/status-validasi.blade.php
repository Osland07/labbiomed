@if ($laporan->status_validasi == 'Menunggu Laboran')
    <span class="badge badge-warning">Menunggu Validasi Laboran</span>
@elseif ($laporan->status_validasi == 'Menunggu Koordinator')
    <span class="badge badge-info">Menunggu Validasi Koordinator</span>
@elseif ($laporan->status_validasi == 'Diterima')
    <span class="badge badge-success">Diterima - Surat Tersedia</span>
@elseif ($laporan->status_validasi == 'Ditolak')
    <span class="badge badge-danger">Ditolak</span>
@elseif ($laporan->status_validasi == 'Selesai')
    <span class="badge badge-success">Tervalidasi</span>
@elseif (empty($laporan->status_validasi))
    <span class="badge badge-secondary">Status Belum Diatur</span>
@else
    <span class="badge badge-secondary">{{ $laporan->status_validasi }}</span>
@endif 