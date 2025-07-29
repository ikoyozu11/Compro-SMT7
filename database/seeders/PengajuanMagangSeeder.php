<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajuanMagangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengajuan_magang')->insert([
            [
                'nama' => 'Rizky Maulana',
                'email' => 'rizky@example.com',
                'no_telp' => '081234567890',
                'instansi' => 'Universitas Indonesia',
                'jurusan' => 'Teknik Informatika',
                'status' => 'pending',
                'catatan' => null,
                'file_surat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'no_telp' => '082134567891',
                'instansi' => 'Universitas Gadjah Mada',
                'jurusan' => 'Sistem Informasi',
                'status' => 'diterima',
                'catatan' => 'Diterima mulai 1 Agustus',
                'file_surat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
