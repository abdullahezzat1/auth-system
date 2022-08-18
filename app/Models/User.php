<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'auth_users';
    protected $primaryKey = 'id';
    public const LENGTHS = [
        'first_name' => 30,
        'last_name' => 30,
        'email' => 254,
    ];
}
