<?php

namespace App\Http\Controllers\API\Video;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Like_video;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VideoController extends Controller
{
    public function getVideoUrl($slug){
        $video = new VideoResource(Video::where('slug',$slug)->firstOrFail());
        // $video['url'] = "https://huylab.click/videos_hls/$slug/$slug.m3u8";
        if($video){
            return response()->json([
                'success' => true,
                'video' => $video,
            ],ResponseEnum::ACCEPTED);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Not found',
            ],ResponseEnum::NO_CONTENT);
        }
    }

    public function getAll(){
        $videos = VideoResource::collection(Video::orderBy('id','DESC')->get());
        if($videos){
            return response()->json([
                'videos' => $videos,
                'success' => true
            ],ResponseEnum::ACCEPTED);
        }else{
            return response()->json([
                'message' => 'Not found',
                'success' => false
            ],ResponseEnum::NO_CONTENT);
        }
    }

    public function addView($slug){
        $video = Video::where('slug',$slug)->firstOrFail();
        if($video){
            $video->increment('view');
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        }
    }

    public function likeVideo($slug){
        $video = Video::where('slug',$slug)->first();
        $auth = auth()->user();
        $checkLike = Like_video::where([
            'user_id' => $auth->id,
            "video_id" => $video->id,
        ])->first();
        if($checkLike){
            return response()->json([
                'success' => false,
                'message' => 'Liked',
            ]);
        }else{
            $like = Like_video::create([
                'user_id' => $auth->id,
                'video_id' => $video->id,
            ]);
            if($like){
                return response()->json([
                    'success' => true,
                ]);
            }else{
                return response()->json([
                    'success' => false,
                ]);
            }
        }

    }

    public function checkLike($slug){
        $video = Video::where('slug',$slug)->first();
        if($video){
            $auth = auth()->user()->id;
            $check = Like_video::where('user_id',$auth)->where('video_id',$video->id)->first();
            if($check){
                return response()->json([
                    'success' => true,
                    'like_status' => true,
                ],ResponseEnum::ACCEPTED);
            }else{
                return response()->json([
                    'success' => false,
                    'like_status' => false,
                ],ResponseEnum::NO_CONTENT);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ],ResponseEnum::NO_CONTENT);
        }
    }

    public function getVideoUser(){
        $auth = auth()->user();
        $videos = VideoResource::collection(Video::where('user_id',$auth->id)->orderBy('id','DESC')->get());
        if($videos){
            return response()->json([
                'success' => true,
                'videos' => $videos,
            ],ResponseEnum::ACCEPTED);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ],ResponseEnum::BADREQUEST);
        }
    }

}
