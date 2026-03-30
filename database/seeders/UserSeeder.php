<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Password plain di login: "password". Di database hanya hash bcrypt (Hash::make).
        $hashed = Hash::make('password');

        User::updateOrCreate(
            ['email' => 'admin@toy.com'],
            [
                'name' => 'Administrator',
                'password' => $hashed,
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@toy.com'],
            [
                'name' => 'Budi Santoso',
                'password' => $hashed,
                'role' => 'user',
                'phone' => '08123456789',
                'address' => 'Jl. Contoh No. 1, Jakarta',
            ]
        );
    }
}
