<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang';
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
        'email',
        'status',
        'catatan',
        'file_surat',
        'penerimaan_token',
        'anggota',
    ];

    public function lokasi()
    {
        return $this->belongsTo(\App\Models\Lokasi::class, 'lokasi_id');
    }
}