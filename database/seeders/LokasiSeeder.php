<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lokasi')->insert([
            [
                'bidang' => 'Teknologi Informasi',
                'tim' => 'Tim Pengembangan Sistem',
                'quota' => 5,
                'alamat' => 'Jl. Merdeka No.1, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bidang' => 'Kepegawaian',
                'tim' => 'Tim HR',
                'quota' => 3,
                'alamat' => 'Jl. Pegawai No.10, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
