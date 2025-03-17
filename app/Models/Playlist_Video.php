<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist_Video extends Model
{
    use HasFactory;
    protected $table = 'playlist_videos';
    protected $fillable = [
        'playlist_id','video_id'
    ];
}
