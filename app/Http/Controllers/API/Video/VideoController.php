<?php

namespace App\Http\Controllers\API\Video;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function getVideoUrl($slug){
        $video = new VideoResource(Video::where('slug',$slug)->firstOrFail());
        // $video['url'] = "https://huylab.click/videos_hls/$slug/$slug.m3u8";
        return response()->json([
            'success' => true,
            'video' => $video,
        ]);
    }

    public function getAll(){
        $videos = VideoResource::collection(Video::orderBy('id','DESC')->get());

        return response()->json([
            'videos' => $videos,
            'success' => true
        ],ResponseEnum::ACCEPTED);
    }

    public function addView($slug){
        $video = Video::where('slug',$slug)->firstOrFail();
        $video->increment('view');
    }
}
