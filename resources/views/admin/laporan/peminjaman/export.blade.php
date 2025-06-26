<!-- Tombol untuk export -->
<button type="submit" role="button" class="btn btn-sm m-1 btn-success text-white"
    onclick="event.preventDefault(); document.getElementById('export').submit();"><i class="fas fa-download"></i><span
        class="d-none d-sm-inline"> {{ __('Export') }}</span></button>

<!-- Form export -->
<form id="export" action="{{ route('admin.laporan.peminjaman.export') }}" hidden>
    @csrf
</form>
