<?php

namespace App\Http\Controllers\API\User;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserControlle extends Controller
{
    public function __construct(){

    }

    public function getUserInfo(){
        $user = new UserResource(User::find(auth()->user()->id));
        if($user){
            return response()->json([
                'success' => true,
                'user' => $user,
            ],ResponseEnum::ACCEPTED);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ],ResponseEnum::NO_CONTENT);
        }

    }
}
