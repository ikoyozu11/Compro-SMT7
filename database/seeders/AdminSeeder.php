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
        // Admin pertama
        if (!User::where('username', 'admin')->exists()) {
            User::create([
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'name' => 'Admin',
                'role' => 'admin',
                'status' => 1
            ]);
        }

        // Admin kedua
        if (!User::where('username', 'admin2')->exists()) {
            User::create([
                'username' => 'admin2',
                'password' => Hash::make('admin123'),
                'name' => 'Admin 2',
                'role' => 'admin',
                'status' => 1
            ]);
        }
    }
} 