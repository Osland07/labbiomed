<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>
    @include('components.alert')

    {{-- Dashboard untuk Admin/Super Admin --}}
    @if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin'))
        @include('dashboard.admin')
    @endif

    {{-- Dashboard untuk Laboran --}}
    @if(Auth::user()->hasRole('Laboran'))
        @include('dashboard.laboran')
    @endif

    {{-- Dashboard untuk Koordinator Laboratorium --}}
    @if(Auth::user()->hasRole('Koordinator Laboratorium'))
        @include('dashboard.koorlab')
    @endif

    {{-- Dashboard untuk Dosen --}}
    @if(Auth::user()->hasRole('Dosen'))
        @include('dashboard.dosen')
    @endif

    {{-- Dashboard untuk Mahasiswa --}}
    @if(Auth::user()->hasRole('Mahasiswa'))
        @include('dashboard.mahasiswa')
    @endif
</x-admin-layout> 