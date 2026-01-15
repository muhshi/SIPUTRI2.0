<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // kalau sudah ada, update saja
            [
                'name' => 'Admin',
                'password' => Hash::make('admin'), // password di-hash
                'email_verified_at' => now(),
            ]
        );
    }
}
