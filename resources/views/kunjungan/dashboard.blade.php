<x-guest-layout>
    @include('components.alert')
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Sistem Kunjungan Laboratorium</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Pilih ruangan untuk melakukan check-in atau check-out dengan mudah dan cepat</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Ruangan</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $ruangans->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Kunjungan Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $todayVisits }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Sedang di Lab</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $activeVisitsCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Scan QR Code</p>
                            <a href="{{ route('kunjungan.scan') }}" class="text-xl font-semibold text-purple-600 hover:text-purple-700 transition-colors">Mulai Scan</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ruangan Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($ruangans as $ruangan)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Ruangan Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold">{{ $ruangan->name }}</h3>
                                <p class="text-blue-100 text-base">{{ $ruangan->gedung ?? 'Gedung' }} - Lantai {{ $ruangan->lantai ?? '1' }}</p>
                            </div>
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- QR Codes -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <!-- Check-in QR -->
                            <div class="text-center">
                                <div class="bg-green-50 rounded-xl p-4 mb-3 border border-green-200">
                                    <div id="qrcode-checkin-{{ $ruangan->id }}" class="w-32 h-32 mx-auto"></div>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">Check-in</p>
                            </div>

                            <!-- Check-out QR -->
                            <div class="text-center">
                                <div class="bg-red-50 rounded-xl p-4 mb-3 border border-red-200">
                                    <div id="qrcode-checkout-{{ $ruangan->id }}" class="w-32 h-32 mx-auto"></div>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">Check-out</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <a href="{{ route('kunjungan.checkin', $ruangan->id) }}" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-base font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg block text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Check-in
                                </div>
                            </a>
                            <a href="{{ route('kunjungan.checkout', $ruangan->id) }}" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-base font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg block text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Check-out
                                </div>
                            </a>
                        </div>

                        <!-- Status -->
                        <div class="mt-6 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $ruangan->status === 'Tersedia' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <span class="w-2 h-2 rounded-full {{ $ruangan->status === 'Tersedia' ? 'bg-green-400' : 'bg-yellow-400' }} mr-2"></span>
                                {{ $ruangan->status }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="mt-12 bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('kunjungan.scan') }}" class="flex items-center p-6 bg-purple-50 rounded-xl hover:bg-purple-100 transition-all duration-200 transform hover:scale-105">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Scan QR Code</p>
                            <p class="text-sm text-gray-600">Scan QR code untuk akses cepat</p>
                        </div>
                    </a>

                    @auth
                    <a href="{{ route('client.riwayat-kunjungan') }}" class="flex items-center p-6 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all duration-200 transform hover:scale-105">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Riwayat Kunjungan</p>
                            <p class="text-sm text-gray-600">Lihat riwayat kunjungan Anda</p>
                        </div>
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="flex items-center p-6 bg-green-50 rounded-xl hover:bg-green-100 transition-all duration-200 transform hover:scale-105">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Login</p>
                            <p class="text-sm text-gray-600">Login untuk akses lebih cepat</p>
                        </div>
                    </a>
                    @endauth

                    <a href="{{ route('beranda') }}" class="flex items-center p-6 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-200 transform hover:scale-105">
                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-gray-800">Kembali ke Beranda</p>
                            <p class="text-sm text-gray-600">Kembali ke halaman utama</p>
                        </div>
                    </a>
                </div>
            </div>

            @guest
            <!-- Info untuk Tamu Umum -->
            <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-8 border border-blue-200">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Selamat Datang, Tamu!</h3>
                        <p class="text-gray-700 mb-4">Anda dapat menggunakan sistem kunjungan laboratorium ini tanpa perlu login. Namun, jika Anda login, proses check-in akan lebih cepat karena data Anda sudah tersimpan.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-4 border border-blue-200">
                                <h4 class="font-semibold text-gray-800 mb-2">Tanpa Login:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Isi data lengkap setiap kali check-in</li>
                                    <li>• Cocok untuk tamu umum</li>
                                    <li>• Tidak ada riwayat kunjungan</li>
                                </ul>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-blue-200">
                                <h4 class="font-semibold text-gray-800 mb-2">Dengan Login:</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Hanya isi tujuan kunjungan</li>
                                    <li>• Data otomatis tersimpan</li>
                                    <li>• Ada riwayat kunjungan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endguest
        </div>
    </div>

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <script>
        // Generate QR Codes for each room
        document.addEventListener('DOMContentLoaded', function() {
            const ruangans = @json($ruangans);
            
            ruangans.forEach(function(ruangan) {
                // Generate Check-in QR Code
                const checkinUrl = '{{ route("kunjungan.checkin", ":id") }}'.replace(':id', ruangan.id);
                QRCode.toCanvas(document.getElementById(`qrcode-checkin-${ruangan.id}`), checkinUrl, {
                    width: 128,
                    height: 128,
                    margin: 2,
                    color: {
                        dark: '#1F2937',
                        light: '#FFFFFF'
                    }
                });
                
                // Generate Check-out QR Code
                const checkoutUrl = '{{ route("kunjungan.checkout", ":id") }}'.replace(':id', ruangan.id);
                QRCode.toCanvas(document.getElementById(`qrcode-checkout-${ruangan.id}`), checkoutUrl, {
                    width: 128,
                    height: 128,
                    margin: 2,
                    color: {
                        dark: '#1F2937',
                        light: '#FFFFFF'
                    }
                });
            });
        });
    </script>
</x-guest-layout> 