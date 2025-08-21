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
            ['email' => 'adminpst3321@gmail.com'], // kalau sudah ada, update saja
            [
                'name' => 'Admin',
                'password' => Hash::make('3321'), // password di-hash
                'email_verified_at' => now(),
            ]
        );
    }
}
