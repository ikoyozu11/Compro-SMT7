<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = 'user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'name',
        'password',
        'role',
        'status',
        'birth_date',
        'address',
        'phone',
        'institution',
    ];

}
