<x-guest-layout>
    @include('components.alert')
    
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100 py-8 px-4">
        <div class="max-w-md mx-auto">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Scan QR Code</h2>
                    <p class="text-gray-600">Scan QR code untuk akses cepat</p>
                </div>
            </div>

            <!-- Scanner Card -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Arahkan Kamera ke QR Code</h3>
                    
                    <!-- Camera Container -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <div id="reader" class="w-full max-w-sm mx-auto"></div>
                        <p class="text-sm text-gray-600 mt-4">Pastikan QR code terlihat jelas di dalam kotak</p>
                    </div>
                    
                    <!-- Manual Input -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-blue-800 font-medium mb-1">Atau masukkan URL manual:</p>
                                <form method="POST" action="{{ route('kunjungan.scan.qr') }}" class="mt-2">
                                    @csrf
                                    <div class="flex">
                                        <input type="text" name="qr_data" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Paste URL QR code di sini">
                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button id="startScan" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 shadow-lg">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Mulai Scan
                            </div>
                        </button>
                        <button id="stopScan" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-lg hidden">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Stop Scan
                            </div>
                        </button>
                        <a href="{{ route('beranda') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 block text-center">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <script>
        let html5QrcodeScanner = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            const startButton = document.getElementById('startScan');
            const stopButton = document.getElementById('stopScan');
            const reader = document.getElementById('reader');
            
            startButton.addEventListener('click', function() {
                startScanning();
                startButton.classList.add('hidden');
                stopButton.classList.remove('hidden');
            });
            
            stopButton.addEventListener('click', function() {
                stopScanning();
                stopButton.classList.add('hidden');
                startButton.classList.remove('hidden');
            });
        });
        
        function startScanning() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                }
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
        
        function stopScanning() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }
        }
        
        function onScanSuccess(decodedText, decodedResult) {
            // Redirect to the scanned URL
            window.location.href = decodedText;
        }
        
        function onScanFailure(error) {
            // Handle scan failure silently
            console.warn(`QR scan error = ${error}`);
        }
    </script>
</x-guest-layout> 