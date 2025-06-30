@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika di halaman penggunaan ruangan
            @if (request()->routeIs('client.penggunaan-ruangan.store'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    background: '#fff5f5',
                    color: '#b91c1c'
                });
            @else
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    background: '#fff5f5',
                    color: '#b91c1c'
                });
            @endif
        });
    </script>
@endif

@if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('message') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#f0fff4',
                color: '#155724'
            });
        });
    </script>
@endif
