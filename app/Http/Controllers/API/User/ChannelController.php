<?php

namespace App\Http\Controllers\API\User;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use App\Models\Chanel_Subsription;
use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function __construct(){

    }

    public function getChannel($id){
        $user = User::find($id);
        if($user){
            $channel = new ChannelResource($user);
            return response()->json([
                'channel' => $channel,
                'success' => true,
            ],ResponseEnum::ACCEPTED);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ],ResponseEnum::NOTFOUND);
        }
    }

    public function createSub($id){
        $auth = auth()->user();
        $findUser = User::find($id);
        if($findUser){
            if($auth->id != $findUser->id){
                $create = Chanel_Subsription::create([
                    'user_id' => $findUser->id,
                    'user_id_sub' => $auth->id,
                ]);
                if($create){
                    return response()->json([
                        'success' => true,
                        'message' => 'Follow the channel successfully',
                    ],ResponseEnum::ACCEPTED);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Some errors, please try again',
                    ],ResponseEnum::BADREQUEST);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'User must different',
                ],ResponseEnum::BADREQUEST);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ],ResponseEnum::BADREQUEST);
        }
    }
}
