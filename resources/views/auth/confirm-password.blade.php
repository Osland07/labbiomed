@extends('layouts.auth.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 to-white">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('assets/logo.jpg') }}" alt="Logo" class="h-16 mb-2">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Konfirmasi Password</h1>
                <p class="text-gray-500 text-center text-sm">Ini adalah area aman. Silakan masukkan password kamu untuk
                    melanjutkan.</p>
            </div>
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-1" for="password">Password</label>
                    <input id="password" name="password" type="password" required placeholder="Masukkan password kamu"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <button type="submit"
                    class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold hover:from-blue-600 hover:to-indigo-600 transition">Konfirmasi</button>
            </form>
        </div>
    </div>
@endsection
