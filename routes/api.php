<?php

use App\Http\Controllers\API\User\LoginSocialController;
use App\Http\Controllers\API\User\UserControlle;
use App\Http\Controllers\API\Video\DeleteVideoController;
use App\Http\Controllers\API\Video\UploadVideoController;
use App\Http\Controllers\API\Video\VideoController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function(){
    Route::get('/google/redirect',[LoginController::class, 'google_redirect']);
    Route::get('/google/callback',[LoginController::class, 'google_callback']);

    Route::post('/login',[LoginSocialController::class, 'login']);
}); //Login sociallite

Route::group(['prefix' => 'video'],function(){
    Route::post('/upload',[UploadVideoController::class, 'uploadVideo'])->middleware('auth:sanctum'); //Upload video
    Route::get('/get-all-video',[VideoController::class, 'getAll']); //Lấy danh sách video
    Route::get('/get/{slug}',[VideoController::class, 'getVideoUrl']); //Lấy video chi tiết
    Route::get('add-view/{slug}',[VideoController::class, 'addView']);
    Route::get('/like/{slug}',[VideoController::class, 'likeVideo'])->middleware('auth:sanctum');
    Route::get('/delete/{slug}',[DeleteVideoController::class, 'deleteVideo'])->middleware('auth:sanctum'); //Xóa video
    Route::get('/get-video-user',[VideoController::class,'getVideoUser'])->middleware('auth:sanctum'); //Lấy video user
    Route::get('/check-like/{slug}',[VideoController::class, 'checkLike'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'user'], function(){
    Route::get('/info', [UserControlle::class, 'getUserInfo'])->middleware('auth:sanctum'); //Lấy thông tin người dùng
});

Route::get('/device-info', [DeviceController::class, 'getDeviceInfo']);

