<x-guest-layout>
    @include('components.alert')
    
    @if(session('success'))
        <div class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg max-w-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg max-w-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out">
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg shadow-lg max-w-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('warning') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('info'))
        <div class="fixed top-4 right-4 z-50 transition-all duration-300 ease-in-out">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg shadow-lg max-w-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('info') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Check-in Kunjungan</h1>
                <p class="text-gray-600">Ruangan: <span class="font-semibold text-blue-600">{{ $ruangan->name }}</span></p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form method="POST" action="{{ route('kunjungan.checkin.store', $ruangan->id) }}" class="space-y-6" id="checkinForm">
                    @csrf
                    
                    @if($user)
                        <!-- User yang sudah login - hanya perlu tujuan -->
                        <div class="space-y-4">
                            <!-- Info User -->
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-blue-600 font-medium">Anda login sebagai</p>
                                        <p class="text-lg font-semibold text-gray-800">{{ $user->name }}</p>
                                        @if($user->nim_nip)
                                            <p class="text-sm text-gray-600">{{ $user->nim_nip }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tujuan Kunjungan -->
                            <div>
                                <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tujuan Kunjungan <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="tujuan" 
                                    name="tujuan" 
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                    placeholder="Jelaskan tujuan kunjungan Anda ke ruangan ini..."
                                    required
                                >{{ old('tujuan') }}</textarea>
                                @error('tujuan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @else
                        <!-- User yang tidak login - perlu mengisi lengkap -->
                        <div class="space-y-4">
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="nama" 
                                    name="nama" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Masukkan nama lengkap Anda"
                                    value="{{ old('nama') }}"
                                    required
                                >
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIM/NIP -->
                            <div>
                                <label for="nim_nip" class="block text-sm font-medium text-gray-700 mb-2">
                                    NIM/NIP
                                </label>
                                <input 
                                    type="text" 
                                    id="nim_nip" 
                                    name="nim_nip" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Masukkan NIM atau NIP (opsional)"
                                    value="{{ old('nim_nip') }}"
                                >
                                @error('nim_nip')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Instansi -->
                            <div>
                                <label for="instansi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Instansi
                                </label>
                                <input 
                                    type="text" 
                                    id="instansi" 
                                    name="instansi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                    placeholder="Masukkan nama instansi (opsional)"
                                    value="{{ old('instansi') }}"
                                >
                                @error('instansi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tujuan Kunjungan -->
                            <div>
                                <label for="tujuan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tujuan Kunjungan <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="tujuan" 
                                    name="tujuan" 
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                    placeholder="Jelaskan tujuan kunjungan Anda ke ruangan ini..."
                                    required
                                >{{ old('tujuan') }}</textarea>
                                @error('tujuan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button 
                            type="submit" 
                            class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg"
                            id="submitBtn"
                        >
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span id="submitText">Check-in Sekarang</span>
                                <div id="loadingSpinner" class="hidden ml-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        
                        <a 
                             href="{{ route('kunjungan.checkout', $ruangan->id) }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center"
                        >
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali
                            </div>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Informasi Penting</h3>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Pastikan data yang diisi sudah benar dan lengkap</li>
                            <li>• Jangan lupa untuk melakukan check-out setelah selesai</li>
                            <li>• Jika ada pertanyaan, silakan hubungi admin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('checkinForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitText.textContent = 'Memproses...';
            loadingSpinner.classList.remove('hidden');
        });

        // Auto-hide notifications after 5 seconds
        setTimeout(function() {
            const notifications = document.querySelectorAll('.fixed.top-4.right-4');
            notifications.forEach(function(notification) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    notification.remove();
                }, 300);
            });
        }, 5000);
    </script>
</x-guest-layout>