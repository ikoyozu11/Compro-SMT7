<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPenelitian extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_penelitian';

    protected $fillable = [
        'nama',
        'instansi',
        'jurusan',
        'judul_penelitian',
        'metode',
        'surat_izin',
        'proposal',
        'daftar_pertanyaan',
        'ktp',
        'status',
        'tanggal_pelaksanaan',
        'surat_selesai',
        'keterangan_penolakan',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Pengajuan' => 'badge bg-warning',
            'Diterima' => 'badge bg-success',
            'Ditolak' => 'badge bg-danger',
        ];
        
        return $badges[$this->status] ?? 'badge bg-secondary';
    }
} 