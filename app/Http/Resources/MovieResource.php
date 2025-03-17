<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MovieResource extends JsonResource
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
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'thumbnail' => $this->thumbnail,
            'view' => $this->view,
            'slug' => $this->slug,
            'url' => env('APP_URL') . "movie/get-file-movie/$this->slug",
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
