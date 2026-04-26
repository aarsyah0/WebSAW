@extends('layouts.guest')

@section('title', 'Verifikasi Reset Password')

@section('content')
<div class="max-w-md mx-auto py-12">
    <x-card class="shadow-md">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Verifikasi email</h1>
        <p class="mt-1 text-sm text-gray-500">Masukkan kode 6 digit yang dikirim ke email Anda.</p>

        @if (session('status'))
            <div class="mt-4">
                <x-alert variant="success">
                    {{ session('status') }}
                </x-alert>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf

            <x-input
                label="Email"
                name="email"
                type="email"
                value="{{ old('email', $email) }}"
                placeholder="nama@email.com"
                required
                autocomplete="email"
                :error="$errors->first('email')"
            />

            <x-input
                label="Kode Verifikasi"
                name="code"
                type="text"
                value="{{ old('code') }}"
                placeholder="123456"
                required
                maxlength="6"
                inputmode="numeric"
                :error="$errors->first('code')"
            />

            <x-input
                label="Password Baru"
                name="password"
                type="password"
                placeholder="Min. 8 karakter"
                required
                autocomplete="new-password"
                :error="$errors->first('password')"
            />

            <x-input
                label="Konfirmasi Password Baru"
                name="password_confirmation"
                type="password"
                placeholder="Ulangi password baru"
                required
                autocomplete="new-password"
            />

            <x-button type="submit" variant="primary" size="lg" class="w-full">Simpan password baru</x-button>
        </form>
    </x-card>
</div>
@endsection
