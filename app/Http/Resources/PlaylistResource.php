<?php

namespace App\Http\Resources;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'videos' => $this->getVideo($this->getPlaylistVideo),
            'total_videos' => count($this->getPlaylistVideo),
            'user' => $this->getUser,
        ];
    }

    private function getVideo($videos_id){
        $videos = [];
        foreach($videos_id as $key => $video_id){
            $videos[] = new VideoResource(Video::where('id',$video_id->video_id)->where('verified',1)->where('status','public')->first());
        }
        return $videos;
    }
}
