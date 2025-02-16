<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->getAvatar($this->avatar),
        ];
    }

    private function getAvatar($avatar){
        if(filter_var($avatar, FILTER_VALIDATE_URL)){
            return $avatar;
        }
        return basename($avatar);
    }
}
