<x-guest-layout>
    @include('components.alert')
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-md mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">QR Code Check-in</h2>
                    <p class="text-gray-600">Scan QR code untuk melakukan check-in</p>
                </div>
                
                <!-- Ruangan Info -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">Ruangan</p>
                            <p class="text-white text-lg font-bold">{{ $ruangan->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Scan QR Code Ini</h3>
                    
                    <!-- QR Code Container -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <div id="qrcode" class="flex justify-center mb-4"></div>
                        <p class="text-sm text-gray-600">Gunakan aplikasi QR scanner di smartphone Anda</p>
                    </div>
                    
                    <!-- QR Code Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-blue-800 font-medium mb-1">Cara menggunakan QR Code:</p>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Buka aplikasi QR scanner di smartphone</li>
                                    <li>• Arahkan kamera ke QR code di atas</li>
                                    <li>• Klik link yang muncul untuk check-in</li>
                                    <li>• Isi form check-in yang tersedia</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('kunjungan.checkin', $ruangan->id) }}" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg block text-center">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Check-in Manual
                            </div>
                        </a>
                        <a href="{{ route('kunjungan.qr.checkout', $ruangan->id) }}" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg block text-center">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                QR Code Check-out
                            </div>
                        </a>
                        <a href="{{ route('beranda') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 block text-center">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            const qrData = @json($qrData);
            const qrUrl = qrData.url;
            
            QRCode.toCanvas(document.getElementById('qrcode'), qrUrl, {
                width: 200,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function (error) {
                if (error) {
                    console.error('Error generating QR code:', error);
                    document.getElementById('qrcode').innerHTML = '<p class="text-red-500">Error generating QR code</p>';
                }
            });
        });
    </script>
</x-guest-layout> 