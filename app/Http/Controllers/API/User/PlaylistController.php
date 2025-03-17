<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlaylistResource;
use App\Models\Playlist;
use App\Models\Playlist_Video;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function __construct()
    {

    }

    public function create(Request $request)
    {
        $auth = auth()->user();
        // Giải mã JSON trước khi sử dụng
        $videos = json_decode($request->videos, true);

        if (!is_array($videos)) {
            return response()->json(['error' => 'Invalid videos format'], 400);
        }
        $playlist = Playlist::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $auth->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        if ($playlist) {
            foreach ($videos as $key => $video) {
                Playlist_Video::create([
                    'playlist_id' => $playlist->id,
                    'video_id' => $video['id'],
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Success'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please try again',
            ]);
        }
    }

    public function getPlaylist(){
        $auth = auth()->user();
        $value = Playlist::where('user_id',$auth->id)->orderBy('id','DESC')->get();
        $playlists = PlaylistResource::collection($value);
        return response()->json([
            'success' => true,
            'playlists' => $playlists,
        ]);
    }

    public function getPlaylistDetail($id){
        $find = Playlist::where('id',$id)->first();
        if($find){
            $playlist = new PlaylistResource($find);
            return response()->json([
                'success' => true,
                'playlist' => $playlist,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Playlist not found',
            ]);
        }
    }

    public function deletePlaylist($id){
        $auth = auth()->user();
        $find = Playlist::where('id',$id)->where('user_id',$auth->id)->first();
        if($find){
            $find->getPlaylistVideo()->delete();
            $find->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted playlist',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Playlist not found'
            ]);
        }
    }

    public function deleteVideo($playlistid,$videoid){
        $auth = auth()->user();
        $playlist = Playlist::where('user_id',$auth->id)->where('id',$playlistid)->first();
        $findVideo = Playlist_Video::where('playlist_id',$playlist->id)->where('video_id',$videoid)->first();
        if($playlist && $findVideo){
            $findVideo->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted video',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ]);
        }
    }

}
