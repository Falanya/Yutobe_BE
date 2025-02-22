<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chanel_Subsription extends Model
{
    use HasFactory;
    protected $table = 'chanel__subsriptions';
    protected $fillable = [
        'user_id','user_id_sub',
    ];
}
