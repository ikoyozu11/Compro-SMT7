<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $dates = ['time'];

    protected $casts = [
        'time' => 'datetime',
    ];

    // Accessor to automatically convert time to WIB
    public function getTimeAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta');
    }
}
