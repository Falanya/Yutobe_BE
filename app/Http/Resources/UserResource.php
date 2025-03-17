<?php

namespace App\Http\Resources;

use App\Models\Block_User;
use App\Models\Premium_status;
use Carbon\Carbon;
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->getAvatar($this->avatar),
            'videos' => count($this->getVideo),
            'premium' => $this->getPremium($this->id),
            'expired_premium' => $this->getExpiredPremium($this->id),
            'isBlock' => $this->checkBlock($this->id),
            'expired_block' => $this->getExpiredBlock($this->id)
        ];
    }

    private function getAvatar($avatar){
        if(filter_var($avatar, FILTER_VALIDATE_URL)){
            return $avatar;
        }
        return basename($avatar);
    }

    private function getPremium($id){
        $check = Premium_status::where('user_id',$id)->first();
        if ($check && Carbon::parse($check->expired_at)->gte(Carbon::today())) {
            return 1;
        }
        return 0;
    }

    private function getExpiredPremium($id) {
        $check = Premium_status::where('user_id', $id)->first();

        if ($check && Carbon::parse($check->expired_at)->gte(Carbon::today())) {
            return Carbon::parse($check->expired_at)->format('d/m/Y');
        }

        return 0;
    }

    private function checkBlock($id){
        $check = Block_User::where('user_id',$id)->first();
        if($check && Carbon::parse($check->expired_at)->gte(Carbon::today())){
            return 1;
        }
        return 0;
    }

    private function getExpiredBlock($id){
        $check = Block_User::where('user_id',$id)->first();
        if($check && Carbon::parse($check->expired_at)->gte(Carbon::today())){
            return Carbon::parse($check->expired_at)->format('d/m/Y');
        }
        return 0;
    }
}
