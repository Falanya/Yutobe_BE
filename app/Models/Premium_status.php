<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Premium_status extends Model
{
    use HasFactory;
    protected $table = 'premium_statuses';
    protected $fillable = [
        'user_id','premium_id','expired_at',
    ];
}
