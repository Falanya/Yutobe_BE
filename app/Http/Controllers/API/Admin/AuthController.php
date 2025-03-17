<?php

namespace App\Http\Controllers\API\Admin;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|email|unique:admins',
            'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ],ResponseEnum::BADREQUEST);
        }
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => 'https://yt3.googleusercontent.com/jJimrc2ErRCgrhWHwdmHACVL7nX7CPWs_sdnLbVx32-JbcR9Qe1FMXHKSHxM8p-JC6utE_KvxRY=s160-c-k-c0x00ffffff-no-rj',
        ]);
        if($admin){
            return response()->json([
                'admin' => $admin,
                'success' => true,
            ],ResponseEnum::ACCEPTED);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Some errors, please try again'
            ],ResponseEnum::BADREQUEST);
        }
    }

    public function login(AdminLoginRequest $request){
        $value = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if(Auth::guard('admin')->attempt($value)){
            // $user = Admin::where('email',$request->email)->first();
            $user = Auth::guard('admin')->user();
            $token = $user->createToken(
                'admin', ['*'],
            )->plainTextToken;
            return response()->json([
                'success' => true,
                'token' => $token,
            ],ResponseEnum::ACCEPTED);
        }
    }
}
