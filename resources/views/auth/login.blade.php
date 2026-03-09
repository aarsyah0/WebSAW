@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="max-w-md mx-auto py-12">
    <x-card class="shadow-md">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Masuk ke akun</h1>
        <p class="mt-1 text-sm text-gray-500">Gunakan email dan password Anda.</p>
        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-6">
            @csrf
            <x-input label="Email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus autocomplete="email" />
            <x-input label="Password" name="password" type="password" required placeholder="••••••••" autocomplete="current-password" />
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-primary-500 focus:ring-primary-500/20">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>
            <x-button type="submit" variant="primary" size="lg" class="w-full">Masuk</x-button>
        </form>
        <p class="mt-6 text-center text-sm text-gray-500">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-700">Daftar</a>
        </p>
    </x-card>
</div>
@endsection
