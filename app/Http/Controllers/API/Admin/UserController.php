<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\Block_User;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {

    }

    public function getUser()
    {
        $auth = auth()->user();
        if ($auth->isAdmin == true) {
            $users = UserResource::collection(User::orderBy('id', 'DESC')->get());
            return response()->json([
                'users' => $users,
                'totalUsers' => count($users),
                'success' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }

    public function getAdmin(){
        $auth = auth()->user();
        if ($auth->isAdmin == true) {
            $admins = AdminResource::collection(Admin::orderBy('id', 'DESC')->get());
            return response()->json([
                'users' => $admins,
                'totalUsers' => count($admins),
                'success' => true,
            ]);
        } else {
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }

    public function blockUser($id){
        $auth = auth()->user();
        if ($auth->isAdmin == true) {
            $find = User::find($id);
            if($find){
                $findBlock = Block_User::where('user_id',$find->id)->first();
                if(!$findBlock){
                    $block = Block_User::create([
                        'user_id' => $find->id,
                        'expired_at' => Carbon::today()->addDays(30),
                    ]);
                    if($block){
                        return response()->json([
                            'success' => true,
                            'message' => 'Blocked user',
                        ]);
                    }

                }else{
                    $findBlock->update([
                        'expired_at' => Carbon::today()->addDays(30),
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }

    public function unBlockUser($id){
        $auth = auth()->user();
        if ($auth->isAdmin == true) {
            $find = User::find($id);
            if($find){
                $findBlock = Block_User::where('user_id',$find->id)->first();
                if($findBlock->delete()){
                    return response()->json([
                        'success' => true,
                        'message' => 'Unblocked user',
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }
}
