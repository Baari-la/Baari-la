<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin JTM',
            'email' => 'admin@jtm.com',
            'password' => Hash::make('admin123'),
            'is_premium' => 1, // Langsung setel sebagai Premium
        ]);
    }
}
