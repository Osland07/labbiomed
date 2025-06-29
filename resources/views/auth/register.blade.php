@extends('layouts.auth.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 to-white">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('assets/logo.jpg') }}" alt="Logo" class="h-16 mb-2">
                <h1 class="text-2xl font-bold text-gray-800">Daftar Akun</h1>
                <p class="text-gray-500 text-center text-sm">Sistem Informasi Laboratorium Biomedis</p>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                {{-- nama lengkap --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="name">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required autofocus
                        placeholder="Masukkan nama lengkap kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                {{-- email --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email Address</label>
                    <input id="email" name="email" type="email" required placeholder="Masukkan email kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                {{-- nim --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="nim">NIM</label>
                    <input id="nim" name="nim" type="text" required placeholder="Masukkan NIM kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                {{-- prodi --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="prodi">Prodi</label>
                    <select name="prodi" id="prodi"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Pilih Prodi</option>
                        <option value="Teknik Biomedis">Teknik Biomedis</option>
                    </select>
                </div>

                {{-- no hp --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="no_hp">No HP</label>
                    <input id="no_hp" name="no_hp" type="text" required placeholder="Masukkan No HP kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                {{-- password --}}
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="password">Password</label>
                    <input id="password" name="password" type="password" required placeholder="Masukkan password kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-1" for="password_confirmation">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        placeholder="Ulangi password kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <button type="submit"
                    class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold hover:from-blue-600 hover:to-indigo-600 transition">Daftar</button>
            </form>
            <div class="my-6 flex items-center">
                <div class="flex-grow border-t"></div>
                <span class="mx-2 text-gray-400 text-xs">atau</span>
                <div class="flex-grow border-t"></div>
            </div>
            <div class="text-center text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-500 font-semibold hover:underline">Masuk</a>
            </div>
            <div class="mt-6 text-center text-xs text-gray-400">
                © 2025 <a href="#" class="text-blue-600 font-semibold hover:underline">Lab Teknik Biomedis</a>
            </div>
        </div>
    </div>
@endsection
