<x-guest-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '{{ $icon ?? 'success' }}',
                title: '{!! $title ?? 'Berhasil' !!}',
                html: '{!! $message ?? '' !!}',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            }).then(() => {
                window.location.href = '{{ $redirect ?? '/' }}';
            });
        });
    </script>
    <div class="text-center py-16">
        <h1 class="text-3xl font-bold mb-4">{!! $title ?? 'Berhasil' !!}</h1>
        <div class="text-lg text-gray-700 mb-8">{!! $message ?? '' !!}</div>
        <a href="{{ $redirect ?? '/' }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition">Kembali</a>
    </div>
</x-guest-layout> 