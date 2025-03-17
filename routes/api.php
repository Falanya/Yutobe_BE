<?php

use App\Http\Controllers\API\Admin\AdminController;
use App\Http\Controllers\API\Admin\AuthController;
use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\API\Movie\MovieController;
use App\Http\Controllers\API\Payment\CheckoutControlle;
use App\Http\Controllers\API\Payment\PaymentController;
use App\Http\Controllers\API\User\ChannelController;
use App\Http\Controllers\API\User\LoginSocialController;
use App\Http\Controllers\API\User\PlaylistController;
use App\Http\Controllers\API\User\UserControlle;
use App\Http\Controllers\API\Video\DeleteVideoController;
use App\Http\Controllers\API\Video\UploadVideoController;
use App\Http\Controllers\API\Video\VideoController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Models\Video;
use App\Http\Controllers\VideoEventController;
use App\Http\Controllers\VideoNasController;
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
});

Route::group(['prefix' => 'video'],function(){
    Route::post('/upload',[UploadVideoController::class, 'uploadVideo'])->middleware('auth:sanctum');
    Route::get('/get-all-video',[VideoController::class, 'getAll']);
    Route::get('/suggest-video-in-detail/{slug}',[VideoController::class,'videoSuggestInDetail']);
    Route::get('/get/{slug}',[VideoController::class, 'getVideoUrl']);
    Route::get('/load-video/{slug}',[VideoController::class,'loadVideo']);
    Route::get('add-view/{slug}',[VideoController::class, 'addView']);
    Route::get('/like/{slug}',[VideoController::class, 'likeVideo'])->middleware('auth:sanctum');
    Route::get('/dislike/{slug}',[VideoController::class,'dislikeVideo'])->middleware('auth:sanctum');
    Route::get('/delete/{slug}',[DeleteVideoController::class, 'deleteVideo'])->middleware('auth:sanctum');
    Route::get('/get-video-user',[VideoController::class,'getVideoUser'])->middleware('auth:sanctum');
    Route::get('/get-video-user-all',[VideoController::class,'getVideoUserAll'])->middleware('auth:sanctum');
    Route::get('/check-like/{slug}',[VideoController::class, 'checkLike'])->middleware('auth:sanctum');
    Route::get('/get-video-like',[VideoController::class,'getVideoLike'])->middleware('auth:sanctum');
    Route::get('/delete-video-like/{id}',[VideoController::class,'deleteVideoLike'])->middleware('auth:sanctum');
    Route::get('/delete-all-video-like',[VideoController::class,'deleteAllVideoLike'])->middleware('auth:sanctum');
    Route::post('/edit-video/{slug}',[VideoController::class,'editVideo'])->middleware('auth:sanctum');
    Route::get('/search-video/{keyword}',[VideoController::class,'searchVideo']);
});

Route::group(['prefix' => 'user'], function(){
    Route::get('/info', [UserControlle::class, 'getUserInfo'])->middleware('auth:sanctum');
    Route::post('/edit-info',[UserControlle::class,'editInfo'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'channel'],function(){
    Route::get('/create-sub/{id}',[ChannelController::class,'createSub'])->middleware('auth:sanctum');
    Route::get('/get-channel/{id}',[ChannelController::class,'getChannel']);
    Route::get('/get-my-channel',[ChannelController::class,'getMyChannel'])->middleware('auth:sanctum');
    Route::get('/check-sub-channel/{id}',[ChannelController::class,'checkSub'])->middleware('auth:sanctum');
    Route::get('/cancel-sub/{id}',[ChannelController::class,'cancelSub'])->middleware('auth:sanctum');
    Route::get('/get-sub-channel',[ChannelController::class,'getSubChannel'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'admin'],function(){
    Route::post('/create', [AuthController::class, 'create']);
    Route::post('/login',[AuthController::class,'login']);
    Route::get('/get-my-admin',[AdminController::class,'getMyAdmin'])->middleware('auth:sanctum');
    Route::get('/get-user',[AdminUserController::class,'getUser'])->middleware('auth:sanctum');
    Route::get('/get-admin',[AdminUserController::class,'getAdmin'])->middleware('auth:sanctum');
    Route::get('/get-video/{type}',[AdminVideoController::class,'getVideo'])->middleware('auth:sanctum');
    Route::get('/video-verifition/{slug}',[AdminVideoController::class,'videoVerifition'])->middleware('auth:sanctum');
    Route::get('/delete-video/{slug}',[AdminVideoController::class,'deleteVideo'])->middleware('auth:sanctum');
    Route::get('/block-user/{id}',[AdminUserController::class,'blockUser'])->middleware('auth:sanctum');
    Route::get('/unblock-user/{id}',[AdminUserController::class,'unBlockUser'])->middleware('auth:sanctum');
    Route::get('/get-movie',[AdminUserController::class,'getMovieAdmin'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'movie'],function(){
    Route::get('/get-all-movie',[MovieController::class,'getAllMovie']);
    Route::get('/get-movie/{slug}',[MovieController::class,'getMovie']);
    Route::get('/get-file-movie/{slug}/{filename}',[MovieController::class,'getFileMovie']);
    Route::get('/add-view/{slug}',[MovieController::class,'addView']);

});

Route::group(['prefix' => 'playlist'],function(){
    Route::post('/create',[PlaylistController::class,'create'])->middleware('auth:sanctum');
    Route::get('/get-playlist',[PlaylistController::class,'getPlaylist'])->middleware('auth:sanctum');
    Route::get('/get-playlist-detail/{id}',[PlaylistController::class,'getPlaylistDetail']);
    Route::get('/delete/{id}',[PlaylistController::class,'deletePlaylist'])->middleware('auth:sanctum');
    Route::get('/delete-video/{playlistid}/{videoid}',[PlaylistController::class,'deleteVideo'])->middleware('auth:sanctum');
});

Route::group(['prefix' => 'payment'],function(){
    Route::post('/payos/create-payment-link',[CheckoutControlle::class,'createPaymentLink'])->middleware('auth:sanctum');
    Route::post('/payos/webhook',[PaymentController::class,'handlePayOSWebhook']);
});

Route::get('/device-info', [DeviceController::class, 'getDeviceInfo']);

//test doc video nas


Route::get('/ftp/list-files', [VideoNasController::class, 'listFiles']);
Route::get('/test-video', [VideoNasController::class, 'testVideo']);

Route::get('/load-video/{filename}', [VideoNasController::class, 'streamVideo']);
