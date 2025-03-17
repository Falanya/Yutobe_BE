<?php

namespace App\Http\Controllers\API\Video;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeleteVideoController extends Controller
{
    public function deleteVideo($slug){
        $auth = auth()->user();
        $check = Video::where('slug',$slug)->where('user_id',$auth->id)->firstOrFail();
        if($check){
            if(File::exists(public_path("videos_hls/$slug"))){
                File::delete(public_path("videos_hls/$slug"));
                File::delete(public_path("thumbnails/$check->thumbnail"));
                $check->getLike()->delete();
                $check->getPlaylistVideo()->delete();
                $check->delete();
                // foreach($likes as $key => $like){

                // }
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
                'message' => 'Video not found'
            ],ResponseEnum::NO_CONTENT);
        }

    }
}
