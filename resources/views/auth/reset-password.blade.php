@extends('layouts.auth.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 to-white">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('assets/logo.jpg') }}" alt="Logo" class="h-16 mb-2">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Reset Password</h1>
                <p class="text-gray-500 text-center text-sm">Masukkan email dan password baru kamu.</p>
            </div>
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email Address</label>
                    <input id="email" name="email" type="email" required autofocus
                        value="{{ old('email', $request->email) }}" placeholder="Masukkan email kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1" for="password">Password Baru</label>
                    <input id="password" name="password" type="password" required placeholder="Masukkan password baru"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-1" for="password_confirmation">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        placeholder="Ulangi password baru"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <button type="submit"
                    class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold hover:from-blue-600 hover:to-indigo-600 transition">Reset
                    Password</button>
            </form>
        </div>
    </div>
@endsection
