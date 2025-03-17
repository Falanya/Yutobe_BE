<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct()
    {

    }

    public function getVideo($type)
    {
        $auth = auth()->user();
        if ($auth->isAdmin == true) {
            if ($type == 'unverified') {
                $video = VideoResource::collection(Video::where('verified', 0)->get());
                return response()->json([
                    'videos' => $video,
                    'totalVideos' => count($video),
                    'success' => true,
                ]);
            }elseif($type == 'verified'){
                $video = VideoResource::collection(Video::where('verified', 1)->get());
                return response()->json([
                    'videos' => $video,
                    'totalVideos' => count($video),
                    'success' => true,
                ]);
            }
            return response()->json([
                'message' => 'Video not found',
                'success' => false,
            ]);
        } else {
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }

    public function videoVerifition($slug){
        $auth = auth()->user();
        if($auth->isAdmin == true){
            $video = Video::where('slug',$slug)->first();
            if($video){
                $video->verified = 1;
                $video->save();
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found',
                ]);
            }
        }else{
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }

    public function deleteVideo($slug){
        $auth = auth()->user();
        if($auth->isAdmin == true){
            $video = Video::where('slug',$slug)->first();
            if($video){
                if(File::exists(public_path("videos_hls/$slug"))){
                    File::delete(public_path("videos_hls/$slug"));
                    File::delete(public_path("thumbnails/$video->thumbnail"));
                    $video->getLike()->delete();
                    $video->getPlaylistVideo()->delete();
                    $video->delete();
                    return response()->json([
                        'success' => true,
                        'message' => 'Successfully deleted the video'
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'File not found'
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found',
                ]);
            }
        }else{
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }
}
