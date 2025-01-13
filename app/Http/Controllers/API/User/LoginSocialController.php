<?php

namespace App\Http\Controllers\API\User;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Social_user;
use App\Models\User;
use Illuminate\Http\Request;

class LoginSocialController extends Controller
{
    public function login(LoginRequest $request){
        $emailInput = $request->input('email');
        $nameInput = $request->input('name');
        $avatarInput = $request->input('avatar');
        $social_idInput = $request->input('social_id');
        $checkUser = User::where('email',$emailInput)->first();
        // if($emailInput == 'trolface112003@gmail.com'){
        //     return redirect()->to('Pornhub.com');
        // }
        if(!$checkUser){
            $createUser = User::create([
                'email' => $emailInput,
                'name' => $nameInput,
                'avatar' => $avatarInput,
                'password' => bcrypt('123456789'),
            ]);
            if($createUser){
                Social_user::create([
                    'user_id' => $createUser->id,
                    'social_name' => 'google',
                    'social_id' => $social_idInput,
                ]);
                $token = $createUser->createToken('login', ['*'], now()->addWeek())->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'message' => 'Đăng ký người dùng thành công',
                ],ResponseEnum::OK);
            }else{
                return response()->json([
                    'error' => 'Lỗi tạo dữ liệu người dùng',
                    'status' => 422,
                ]);
            }
        }else{
            $token = $checkUser->createToken('login', ['*'], now()->addWeek())->plainTextToken;
            return response()->json([
                'token' => $token,
                'message' => 'Đăng nhập người dùng thành công',
                'status' => 200,
            ]);
        }
        // return response()->json([
        //     'message' => 123,
        //     'error' => 'Con cặc',
        //     'email'=> $emailInput,
        // ]);
    }

}
