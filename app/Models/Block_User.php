<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block_User extends Model
{
    use HasFactory;
    protected $table = 'block_users';
    protected $fillable = [
        'user_id','expired_at'
    ];
}
