<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Selamat Datang di Sistem Laboratorium Biomedik</h1>
                    
                    <!-- Quick Access Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Kunjungan System -->
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Sistem Kunjungan</h3>
                                    <p class="text-blue-100 text-sm">Check-in & Check-out</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <a href="{{ route('kunjungan.dashboard') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-200">
                                    <i class="fas fa-qrcode mr-2"></i>Dashboard Kunjungan
                                </a>
                                <a href="{{ route('kunjungan.scan') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-200">
                                    <i class="fas fa-camera mr-2"></i>Scan QR Code
                                </a>
                            </div>
                        </div>

                        <!-- Admin Panel -->
                        @if(auth()->user() && auth()->user()->hasRole('admin'))
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Admin Panel</h3>
                                    <p class="text-green-100 text-sm">Kelola Sistem</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <a href="{{ route('admin.dashboard') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-200">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                                </a>
                                <a href="{{ route('admin.kunjungan.index') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-200">
                                    <i class="fas fa-list mr-2"></i>Data Kunjungan
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- User Profile -->
                        @auth
                        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Profil Saya</h3>
                                    <p class="text-purple-100 text-sm">{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <a href="{{ route('profile.edit') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-200">
                                    <i class="fas fa-user-edit mr-2"></i>Edit Profil
                                </a>
                                <a href="{{ route('client.riwayat-kunjungan') }}" class="block w-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-center py-2 px-4 rounded-lg transition-all duration-200">
                                    <i class="fas fa-history mr-2"></i>Riwayat Kunjungan
                                </a>
                            </div>
                        </div>
                        @endauth
                    </div>

                    <!-- QR Code Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V6a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1zm12 0h2a1 1 0 001-1V6a1 1 0 00-1-1h-2a1 1 0 00-1 1v1a1 1 0 001 1zM5 20h2a1 1 0 001-1v-1a1 1 0 00-1-1H5a1 1 0 00-1 1v1a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-blue-800 mb-2">Sistem QR Code Kunjungan</h3>
                                <p class="text-blue-700 mb-3">Sistem kunjungan laboratorium kini dilengkapi dengan QR code untuk memudahkan proses check-in dan check-out. Fitur yang tersedia:</p>
                                <ul class="text-blue-700 space-y-1 text-sm">
                                    <li>• <strong>QR Code Check-in:</strong> Scan QR code untuk melakukan check-in ke ruangan</li>
                                    <li>• <strong>QR Code Check-out:</strong> Scan QR code untuk melakukan check-out dari ruangan</li>
                                    <li>• <strong>Scanner QR Code:</strong> Gunakan kamera untuk scan QR code</li>
                                    <li>• <strong>Dashboard Kunjungan:</strong> Lihat semua ruangan dan QR code dalam satu halaman</li>
                                    <li>• <strong>Riwayat Kunjungan:</strong> Pantau riwayat kunjungan Anda</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    @auth
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                        <div class="space-y-3">
                            @php
                                $recentVisits = \App\Models\Kunjungan::where('nama', auth()->user()->name)
                                    ->latest('waktu_masuk')
                                    ->take(5)
                                    ->get();
                            @endphp
                            
                            @if($recentVisits->count() > 0)
                                @foreach($recentVisits as $visit)
                                <div class="flex items-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="w-8 h-8 {{ $visit->waktu_keluar ? 'bg-green-100' : 'bg-yellow-100' }} rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 {{ $visit->waktu_keluar ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">{{ $visit->ruangan->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $visit->waktu_masuk->format('d M Y H:i') }} - {{ $visit->waktu_keluar ? $visit->waktu_keluar->format('H:i') : 'Masih di lab' }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $visit->waktu_keluar ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $visit->waktu_keluar ? 'Selesai' : 'Aktif' }}
                                    </span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-gray-600 text-center py-4">Belum ada aktivitas kunjungan</p>
                            @endif
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 