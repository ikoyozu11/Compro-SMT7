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
                'nama_pemohon' => 'Rizky Maulana',
                'email' => 'rizky@example.com',
                'no_hp' => '081234567890',
                'asal_instansi' => 'Universitas Indonesia',
                'jurusan' => 'Teknik Informatika',
                'status' => 'pending',
                'file_surat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_pemohon' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'no_hp' => '082134567891',
                'asal_instansi' => 'Universitas Gadjah Mada',
                'jurusan' => 'Sistem Informasi',
                'status' => 'diterima',
                'file_surat' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
