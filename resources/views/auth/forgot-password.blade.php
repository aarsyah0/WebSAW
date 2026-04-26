@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="max-w-md mx-auto py-12">
    <x-card class="shadow-md">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Lupa password</h1>
        <p class="mt-1 text-sm text-gray-500">Masukkan email akun Anda, kami akan kirim kode verifikasi.</p>

        @if (session('status'))
            <div class="mt-4">
                <x-alert variant="success">
                    {{ session('status') }}
                </x-alert>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-6">
            @csrf
            <x-input
                label="Email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
                autofocus
                autocomplete="email"
                :error="$errors->first('email')"
            />

            <x-button type="submit" variant="primary" size="lg" class="w-full">Kirim kode verifikasi</x-button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Ingat password? <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Kembali ke login</a>
        </p>
    </x-card>
</div>
@endsection
