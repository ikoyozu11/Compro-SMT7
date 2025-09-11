<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $table = 'progress';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file_path',
        'file_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
