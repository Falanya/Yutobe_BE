<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social_user extends Model
{
    use HasFactory;
    protected $table = 'social_users';
    protected $fillable = [
        'user_id',
        'social_name',
        'social_id',
        'social_token',
        'social_refresh_token',
    ];
}
