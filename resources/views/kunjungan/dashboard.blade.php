<x-guest-layout>
    @include('components.alert')
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Sistem Kunjungan Laboratorium</h1>
                <p class="text-gray-600">Pilih ruangan untuk melakukan check-in atau check-out</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Ruangan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $ruangans->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Kunjungan Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $todayVisits }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Sedang di Lab</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $activeVisits }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Scan QR Code</p>
                            <a href="{{ route('kunjungan.scan') }}" class="text-lg font-semibold text-purple-600 hover:text-purple-700">Mulai Scan</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ruangan Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($ruangans as $ruangan)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Ruangan Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold">{{ $ruangan->name }}</h3>
                                <p class="text-blue-100 text-sm">{{ $ruangan->gedung ?? 'Gedung' }} - Lantai {{ $ruangan->lantai ?? '1' }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- QR Codes -->
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <!-- Check-in QR -->
                            <div class="text-center">
                                <div class="bg-green-50 rounded-lg p-3 mb-2">
                                    <div id="qrcode-checkin-{{ $ruangan->id }}" class="w-24 h-24 mx-auto"></div>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Check-in</p>
                            </div>

                            <!-- Check-out QR -->
                            <div class="text-center">
                                <div class="bg-red-50 rounded-lg p-3 mb-2">
                                    <div id="qrcode-checkout-{{ $ruangan->id }}" class="w-24 h-24 mx-auto"></div>
                                </div>
                                <p class="text-xs text-gray-600 font-medium">Check-out</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2">
                            <a href="{{ route('kunjungan.checkin', $ruangan->id) }}" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg block text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Check-in
                                </div>
                            </a>
                            <a href="{{ route('kunjungan.checkout', $ruangan->id) }}" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg block text-center">
                                <div class="flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Check-out
                                </div>
                            </a>
                        </div>

                        <!-- Status -->
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ruangan->status === 'Tersedia' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <span class="w-2 h-2 rounded-full {{ $ruangan->status === 'Tersedia' ? 'bg-green-400' : 'bg-yellow-400' }} mr-1"></span>
                                {{ $ruangan->status }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('kunjungan.scan') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Scan QR Code</p>
                            <p class="text-sm text-gray-600">Scan QR code untuk akses cepat</p>
                        </div>
                    </a>

                    @auth
                    <a href="{{ route('client.riwayat-kunjungan') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Riwayat Kunjungan</p>
                            <p class="text-sm text-gray-600">Lihat riwayat kunjungan Anda</p>
                        </div>
                    </a>
                    @endauth

                    <a href="{{ route('beranda') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Kembali ke Beranda</p>
                            <p class="text-sm text-gray-600">Kembali ke halaman utama</p>
                        </div>
                    </a>
                </div>
            </div>
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
                    width: 96,
                    margin: 1,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    }
                });
                
                // Generate Check-out QR Code
                const checkoutUrl = '{{ route("kunjungan.checkout", ":id") }}'.replace(':id', ruangan.id);
                QRCode.toCanvas(document.getElementById(`qrcode-checkout-${ruangan.id}`), checkoutUrl, {
                    width: 96,
                    margin: 1,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    }
                });
            });
        });
    </script>
</x-guest-layout> 