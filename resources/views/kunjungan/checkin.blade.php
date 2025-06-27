<x-guest-layout>
    @include('components.alert')
    
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
                <form method="POST" action="{{ route('kunjungan.checkin.store', $ruangan->id) }}" class="space-y-6">
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
                                ></textarea>
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
                                ></textarea>
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
                        >
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Check-in Sekarang
                            </div>
                        </button>
                        
                        <a 
                            href="{{ route('login') }}" 
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
</x-guest-layout>