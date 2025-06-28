<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Dashboard Kunjungan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <x-favicon />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles for Dashboard -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <!-- Navigation Header -->
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo and Brand -->
                <div class="flex items-center">
                    <a href="{{ route('beranda') }}" class="flex items-center space-x-3">
                        <x-application-logo class="w-5 h-5 fill-current text-white" />
                        <div class="text-white">
                            <h1 class="text-xl font-bold">LabBiomed</h1>
                            <p class="text-xs text-blue-100">Sistem Kunjungan</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('kunjungan.dashboard') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('kunjungan.scan') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        Scan QR
                    </a>
                    @auth
                        <a href="{{ route('client.riwayat-kunjungan') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Riwayat
                        </a>
                        <a href="{{ route('profile.edit') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Profil
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                            Login
                        </a>
                    @endauth
                    <a href="{{ route('beranda') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        Beranda
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-white hover:text-blue-200 focus:outline-none focus:text-blue-200" id="mobile-menu-button">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-blue-300">
                    <a href="{{ route('kunjungan.dashboard') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('kunjungan.scan') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">
                        Scan QR
                    </a>
                    @auth
                        <a href="{{ route('client.riwayat-kunjungan') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">
                            Riwayat
                        </a>
                        <a href="{{ route('profile.edit') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">
                            Profil
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">
                            Login
                        </a>
                    @endauth
                    <a href="{{ route('beranda') }}" class="bg-white bg-opacity-20 text-white block px-3 py-2 rounded-md text-base font-medium">
                        Beranda
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">LabBiomed</h3>
                    <p class="text-gray-300 text-sm">
                        Sistem manajemen kunjungan laboratorium yang modern dan efisien untuk memudahkan proses check-in dan check-out.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Fitur Utama</h3>
                    <ul class="text-gray-300 text-sm space-y-2">
                        <li>• Check-in/Check-out QR Code</li>
                        <li>• Riwayat Kunjungan</li>
                        <li>• Manajemen Ruangan</li>
                        <li>• Laporan Real-time</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="text-gray-300 text-sm space-y-2">
                        <p>Email: info@labbiomed.com</p>
                        <p>Telp: (021) 1234-5678</p>
                        <p>Alamat: Jl. Contoh No. 123</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300 text-sm">
                    © {{ date('Y') }} LabBiomed. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html> 