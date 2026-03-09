@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
<div class="max-w-md mx-auto py-12">
    <x-card class="shadow-md">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar akun baru</h1>
        <p class="mt-1 text-sm text-gray-500">Isi data berikut untuk mulai.</p>
        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-6">
            @csrf
            <x-input label="Nama" name="name" type="text" value="{{ old('name') }}" placeholder="Nama lengkap" required autocomplete="name" />
            <x-input label="Email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autocomplete="email" />
            <x-input label="Password" name="password" type="password" required placeholder="Min. 8 karakter" autocomplete="new-password" />
            <x-input label="Konfirmasi password" name="password_confirmation" type="password" required placeholder="Ulangi password" autocomplete="new-password" />
            <x-button type="submit" variant="primary" size="lg" class="w-full">Daftar</x-button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Masuk</a>
        </p>
    </x-card>
</div>
@endsection
