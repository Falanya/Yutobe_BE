<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $fillable = [
        'title', 'thumbnail','description','user_id','slug','view','status','verified','created_at','updated_at'
    ];

    public function getUser(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getLike(){
        return $this->hasMany(Like_video::class,'video_id');
    }

    public function getPlaylistVideo(){
        return $this->hasMany(Playlist_Video::class,'video_id');
    }
}
