<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PlaylistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('convert-video', [VideoController::class, 'convertVideo']);

Route::get('playlist', [PlaylistController::class, 'playlistVideo'])->name('playlist');

// Route::get('/uploadvideo', [VideoController::class, 'uploadVideo'])->name('uploadvideoget');
Route::post('/uploadvideoo', [VideoController::class, 'uploadVideo'])->name('uploadvideopost');

Route::get('/login', [LoginController::class, 'login_view']);
// Route::get('/auth/google/redirect',[LoginController::class, 'google_redirect'])->name('google.redirect');
// Route::get('/auth/google/callback',[LoginController::class, 'google_callback']);
