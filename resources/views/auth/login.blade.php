@extends('layouts.auth.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 to-white">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('assets/logo.jpg') }}" alt="Logo" class="h-16 mb-2">
                <h1 class="text-2xl font-bold text-gray-800">Selamat Datang!</h1>
                <p class="text-gray-500 text-center text-sm">Sistem Informasi Laboratorium Teknik Biomedis</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                {{-- email --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email Address</label>
                    <input id="email" name="email" type="email" required autofocus placeholder="Masukkan email kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}"
                        oninvalid="this.setCustomValidity('Masukkan email yang valid!')"
                        oninput="this.setCustomValidity('')">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- password --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="password">Password</label>
                    <x-password-input id="password" name="password" required placeholder="Masukkan password kamu" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 {{ (
                        isset(
                            // @php directive for blade
                            // $errors is always available in blade
                            // but for safety, we check
                            $errors) && $errors->has('password') ? 'border-red-500' : ''
                    ) }}" oninvalid="this.setCustomValidity('Silakan isi password Anda!')" oninput="this.setCustomValidity('')" />
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">Lupa
                        password?</a>
                </div>
                <button type="submit"
                    class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold hover:from-blue-600 hover:to-indigo-600 transition">Masuk</button>
            </form>
            <div class="my-6 flex items-center">
                <div class="flex-grow border-t"></div>
                <span class="mx-2 text-gray-400 text-xs">atau</span>
                <div class="flex-grow border-t"></div>
            </div>
            <div class="text-center text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-500 font-semibold hover:underline">Daftar sekarang</a>
            </div>
            <div class="mt-6 text-center text-xs text-gray-400">
                © 2025 <a href="#" class="text-blue-600 font-semibold hover:underline">Lab Teknik Biomedis</a>
            </div>
        </div>
    </div>
@endsection
