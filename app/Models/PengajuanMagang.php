<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang'; // pastikan sesuai dengan nama tabel kamu di database

    protected $fillable = [
        'nama_pemohon',
        'no_hp',
        'nama_anggota',
        'asal_instansi',
        'jurusan',
        'keahlian',
        'lokasi_id',
        'mulai_magang',
        'selesai_magang',
    ];

    // (Opsional) relasi ke lokasi magang
    public function lokasi()
    {
        return $this->belongsTo(LokasiMagang::class);
    }
}