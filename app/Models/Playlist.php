<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;
    protected $table = 'playlists';
    protected $fillable = [
        'title','description','status','user_id','created_at','updated_at'
    ];

    public function getPlaylistVideo(){
        return $this->hasMany(Playlist_Video::class,'playlist_id','id');
    }

    public function getUser(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
