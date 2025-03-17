<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like_video extends Model
{
    use HasFactory;
    protected $table = 'like_videos';
    protected $fillable = [
        'user_id',
        'video_id'
    ];

    public function getVideo(){
        return $this->belongsTo(Video::class,'id','video_id');
    }
}
