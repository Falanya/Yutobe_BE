<?php

namespace App\Http\Controllers\API\User;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserControlle extends Controller
{
    public function __construct(){

    }

    public function getUserInfo(){
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'user' => $user,
        ],ResponseEnum::ACCEPTED);
    }
}
