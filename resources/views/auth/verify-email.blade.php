@extends('layouts.auth.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-blue-100 to-white">
        <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('assets/logo.jpg') }}" alt="Logo" class="h-16 mb-2">
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Verifikasi Email</h1>
                <p class="text-gray-500 text-center text-sm">Sebelum mulai, silakan verifikasi email kamu dengan klik link
                    yang sudah dikirim ke email. Jika belum menerima email, klik tombol di bawah untuk mengirim ulang.</p>
            </div>
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('Link verifikasi baru sudah dikirim ke email kamu.') }}
                </div>
            @endif
            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="py-2 px-4 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold hover:from-blue-600 hover:to-indigo-600 transition">Kirim
                        Ulang Email Verifikasi</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="ml-4 underline text-sm text-gray-600 hover:text-gray-900">Keluar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
