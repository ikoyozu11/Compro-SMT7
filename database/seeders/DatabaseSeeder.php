<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'name' => 'Admin',
            'role' => 'admin',
            'status' => 1
        ]);
        User::create([
            'username' => 'kanonfueee',
            'password' => Hash::make('hyperbeam123'),
            'name' => 'Kanon Fueee',
            'role' => 'magang',
            'status' => 1
        ]);
        $this->call([
        PengajuanPenelitianSeeder::class,
        LokasiSeeder::class,
        PengajuanMagangSeeder::class,
    ]);
    }
}
