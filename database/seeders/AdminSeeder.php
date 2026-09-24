<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'Kalisawahaset1*';

        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            throw new \Exception(
                'Password harus minimal 8 karakter dan mengandung huruf besar, huruf kecil, angka, serta simbol.'
            );
        }

        User::firstOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Admin123',
                'password' => Hash::make('Kalisawahaset1*'),
                'role' => 'admin',
            ]
        );
    }
}