<?php

namespace App\Http\Resources;

use App\Models\Like_video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'thumbnail' => env('APP_URL') . "thumbnails/$this->thumbnail",
            'description' => $this->description,
            'slug' => $this->slug,
            'view' => $this->view,
            'likes' => $this->likes($this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->getUser,
            'url' => env('APP_URL') . "videos_hls/$this->slug/$this->slug.m3u8",
        ];
    }

    private function likes($id){
        $likes = Like_video::where('video_id',$id)->get();
        $count_like = count($likes);
        return $count_like;
    }
}
