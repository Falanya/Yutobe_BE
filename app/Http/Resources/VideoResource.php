<?php

namespace App\Http\Resources;

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
            'thumbnail' => $this->thumbnail,
            'description' => $this->description,
            'slug' => $this->slug,
            'view' => $this->view,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->getUser,
            'url' => "https://huylab.click/videos_hls/$this->slug/$this->slug.m3u8"
        ];
    }
}
