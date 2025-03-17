<?php

namespace App\Http\Resources;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
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
            'name' => $this->name,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'sub' => count($this->getSub),
            'total_video' => count($this->getVideo),
            'videos' => VideoResource::collection($this->getVideo),
            'playlists' => PlaylistResource::collection(Playlist::where('user_id',$this->id)->where('status','public')->orderBy('id','DESC')->get()),
        ];
    }
}
