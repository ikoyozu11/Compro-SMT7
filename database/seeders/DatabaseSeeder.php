<?php

namespace Database\Seeders;

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
        // Jalankan seeder lainnya
        $this->call([
            AdminSeeder::class,
            PengajuanPenelitianSeeder::class,
            LokasiSeeder::class,
            PengajuanMagangSeeder::class,
        ]);

        // Tambahkan user magang jika belum ada
        if (!User::where('username', 'kanonfueee')->exists()) {
            User::create([
                'username' => 'kanonfueee',
                'password' => Hash::make('hyperbeam123'),
                'name' => 'Kanon Fueee',
                'role' => 'magang',
                'status' => 1
            ]);
        }

        // Jalankan seeder lainnya
        $this->call([
            PengajuanPenelitianSeeder::class,
            LokasiSeeder::class,
            PengajuanMagangSeeder::class,
        ]);
    }
}
