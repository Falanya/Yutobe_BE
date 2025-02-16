<?php

namespace App\Http\Controllers\API\User;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditUserController extends Controller
{
    public function __construct(){

    }

    public function editUser(){

    }

    public function changeAvatar(Request $request){
        $validator = Validator::make($request->all(),[
            'avatar' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all(),
            ],ResponseEnum::BADREQUEST);
        }else{
            if($request->hasFile('avatar')){
                $id = uniqid();
                $avatarName = $id. '.' . $request->avatar->getClientOriginalExtension();
                return $this->saveAvatar($request,$avatarName);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ],ResponseEnum::BADREQUEST);
            }
        }
    }

    private function makeDirectoryPublic($directory){
        if(!file_exists($directory)){
            mkdir(public_path($directory),0777,true);
        }
    }
    private function saveAvatar($request,$avatarName){
        $this->makeDirectoryPublic('avatar_users');
        $request->thumbnail->move(public_path('avatar_users'),$avatarName);
        $auth = auth()->user();
        $user = User::find($auth->id);
        $user->avatar = $avatarName;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Changed avatar successfully'
        ]);
    }
}
