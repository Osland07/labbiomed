<x-guest-layout>
    @include('components.alert')
    
    @if(session('success'))
        <div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-100 py-8 px-4">
            <div class="max-w-md mx-auto">
                <!-- Success Card -->
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Check-out Berhasil!</h2>
                    <p class="text-gray-600 mb-6">{{ session('success') }}</p>
                    
                    <!-- Ruangan Info -->
                    <div class="bg-gradient-to-r from-red-500 to-orange-600 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-center">
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
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="{{ route('kunjungan.checkin', $ruangan->id) }}" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg block text-center">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Check-in Lagi
                            </div>
                        </a>
                        <a href="{{ route('beranda') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 block text-center">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-100 py-8 px-4">
            <div class="max-w-md mx-auto">
                <!-- Header Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Check-out Kunjungan</h2>
                        <p class="text-gray-600">Keluar dari ruangan</p>
                    </div>
                    
                    <!-- Ruangan Info -->
                    <div class="bg-gradient-to-r from-red-500 to-orange-600 rounded-xl p-4 mb-6">
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

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <form method="POST" action="{{ route('kunjungan.checkout.store', $ruangan->id) }}">
                        @csrf
                        
                        @if($user)
                            <!-- User is logged in -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-900" value="{{ $user->name }}" disabled>
                                    </div>
                                </div>
                                
                                <!-- Info Box -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm text-blue-800">
                                            Anda akan melakukan check-out dari ruangan ini. Pastikan semua aktivitas telah selesai.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Guest user -->
                            <div class="space-y-4">
                                @if($kunjungans && $kunjungans->count() > 0)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Nama <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <select name="kunjungan_id" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors appearance-none bg-white" required>
                                                <option value="">-- Pilih Nama --</option>
                                                @foreach($kunjungans as $k)
                                                    <option value="{{ $k->id }}">{{ $k->nama }} @if($k->nim_nip)({{ $k->nim_nip }})@endif</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Info Box -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-blue-800 font-medium mb-1">Daftar pengunjung yang belum check-out:</p>
                                                <p class="text-xs text-blue-700">Pilih nama dari daftar di atas untuk melakukan check-out.</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- No active visits -->
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm text-yellow-800 font-medium">Tidak ada kunjungan aktif</p>
                                                <p class="text-xs text-yellow-700">Tidak ada pengunjung yang sedang berada di ruangan ini saat ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Submit Button -->
                        <div class="mt-6">
                            @if(!$user && (!$kunjungans || $kunjungans->count() == 0))
                                <button type="button" disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-6 rounded-lg cursor-not-allowed">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tidak Ada Kunjungan Aktif
                                    </div>
                                </button>
                            @else
                                <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Check-out Sekarang
                                    </div>
                                </button>
                            @endif
                        </div>
                    </form>
                    
                    <!-- Back Link -->
                    <div class="mt-4 text-center">
<<<<<<< HEAD
                        <div class="flex space-x-4 justify-center">
                            <a href="{{ route('kunjungan.qr.checkout', $ruangan->id) }}" class="text-sm text-red-500 hover:text-red-700 transition-colors">
                                <i class="fas fa-qrcode mr-1"></i>QR Code
                            </a>
                            <a href="{{ route('beranda') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                                ← Kembali ke Beranda
                            </a>
                        </div>
=======
                        <a href="{{ route('beranda') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            ← Kembali ke Beranda
                        </a>
>>>>>>> beb307dfe502eedce80aab7aef5cf105f5a248be
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>