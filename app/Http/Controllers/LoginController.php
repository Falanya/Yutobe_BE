<?php

namespace App\Http\Controllers;

use App\Models\Social_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function google_redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
        // return response()->json([
        //     'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        // ]);
    }

    public function google_callback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        dd($user);
        $checkUser = User::where('email', $user->email)->first();
        if(!$checkUser){
            $valueUser = [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'password' => bcrypt(uniqid()),
            ];
            $createUser = User::create($valueUser);
            if($createUser){
                Social_user::create([
                    'user_id' => $createUser->id,
                    'social_name' => 'Google',
                    'social_id' => $user->id,
                    'social_token' => $user->token,
                    'social_refresh_token' => $user->refreshToken,
                ]);
                $token = $createUser->createToken('login',['*'],now()->addMonth())->plainTextToken;
                return response()->json([
                    'user' => $valueUser,
                    'token' => $token,
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'error' => 'Lỗi tạo thông tin người dùng',
                    'status' => 400,
                ]);
            }
        } else {
            $updateUser = Social_user::where('user_id',$checkUser->id)->update(['social_id' => $user->id]);
            if($updateUser){
                $token = $checkUser->createToken('login',['*'],now()->addMonth())->plainTextToken;
                return response()->json([
                    'message' => 'Thay đổi id social thành công',
                    'token' => $token,
                    'status' => 200,
                ]);
            } else{
                return response()->json([
                    'message' => 'Lỗi cập nhật id social',
                    'status' => 400,
                ]);
            }
        }
    }

    public function login_view()
    {
        return view('playlistvideo');
    }

}
