<?php

namespace App\Http\Controllers\API\User;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChannelResource;
use App\Http\Resources\UserResource;
use App\Models\Chanel_Subsription;
use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function __construct(){

    }

    public function getMyChannel(){
        $auth = auth()->user();
        $channel = new ChannelResource($auth);
        return response()->json([
            'channel' => $channel,
            'success' => true,
        ]);
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
                $findSub = Chanel_Subsription::where('user_id',$findUser->id)->where('user_id_sub',$auth->id)->first();
                if(!$findSub){
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
                        'message' => 'Try again',
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

    public function checkSub($id){
        $findUser = User::find($id);
        $auth = auth()->user();
        if($findUser){
            $findSub = Chanel_Subsription::where('user_id',$findUser->id)->where('user_id_sub',$auth->id)->first();
            if($findSub){
                return response()->json([
                    'success' => true,
                    'sub_status' => true,
                ],ResponseEnum::ACCEPTED);
            }else{
                return response()->json([
                    'success' => false,
                    'sub_status' => false,
                ],ResponseEnum::BADREQUEST);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ],ResponseEnum::BADREQUEST);
        }
    }

    public function cancelSub($id){
        $auth = auth()->user();
        $findUser = User::find($id);
        if($findUser){
            $findSub = Chanel_Subsription::where('user_id',$findUser->id)->where('user_id_sub',$auth->id)->first();
            if($findSub){
                $deleteSub = $findSub->delete();
                if($deleteSub){
                    return response()->json([
                        'success' => true,
                        'message' => 'Deleted',
                    ],ResponseEnum::ACCEPTED);
                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Please try again'
                    ],ResponseEnum::BADREQUEST);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data'
                ],ResponseEnum::BADREQUEST);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ],ResponseEnum::BADREQUEST);
        }
    }

    public function getSubChannel(){
        $auth = auth()->user();
        $subs = Chanel_Subsription::where('user_id_sub',$auth->id)->orderBy('id','DESC')->get();
        $channel_id = [];
        $channel = [];
        foreach($subs as $key => $sub){
            $channel_id[] = $sub->user_id;
        }
        for($i = 0; $i < count($channel_id); $i++){
            $channel[] = new ChannelResource(User::where('id',$channel_id[$i])->first());
        }
        return response()->json([
            'channel' => $channel,
            'success' => true
        ]);
    }


}
