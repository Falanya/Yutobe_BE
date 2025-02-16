<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PlaylistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoTestController;
use Illuminate\Support\Facades\Storage;

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
    $image = Storage::url('public/user/images/677ec83e59384.jpg');
    return view('welcome',compact('image'));
});

// Route::get('convert-video', [VideoController::class, 'convertVideo']);

Route::get('playlist', [PlaylistController::class, 'playlistVideo'])->name('playlist');

// Route::get('/uploadvideo', [VideoController::class, 'uploadVideo'])->name('uploadvideoget');
Route::post('/uploadvideoo', [VideoController::class, 'uploadVideo'])->name('uploadvideopost');

Route::get('/login', [LoginController::class, 'login_view']);
// Route::get('/auth/google/redirect',[LoginController::class, 'google_redirect'])->name('google.redirect');
// Route::get('/auth/google/callback',[LoginController::class, 'google_callback']);
// Route để hiển thị danh sách video
Route::get('/videos', [VideoTestController::class, 'index'])->name('video.index');

// Route để phát video HLS
Route::get('/video/{filename}', [VideoTestController::class, 'stream'])->name('video.stream');









Route::get('/list-files', [VideoTestController::class, 'listFiles']);
Route::get('/video/{filename}', [VideoTestController::class, 'getVideo']);

Route::get('/stream-m3u8/{folder}', function ($folder) {
    // Đường dẫn thư mục cần kiểm tra
    $directoryPath = "videos_hls/{$folder}";

    // Lấy danh sách file trong thư mục
    $files = Storage::disk('nas')->files($directoryPath);

    // Tìm file có đuôi .m3u8
    $m3u8File = collect($files)->first(function ($file) {
        return str_ends_with($file, '.m3u8'); // Kiểm tra đuôi file
    });

    // Nếu không tìm thấy file .m3u8
    if (!$m3u8File) {
        return response()->json(['error' => 'No .m3u8 file found'], 404);
    }

    // Đọc nội dung file
    $content = Storage::disk('nas')->get($m3u8File);

    // Thay đổi đường dẫn file .ts bên trong file .m3u8
    $folderUrl = url("/stream-ts/{$folder}/");
    $content = str_replace('segment_', "{$folderUrl}/segment_", $content);

    // Trả nội dung file .m3u8
    return response($content, 200, [
        'Content-Type' => 'application/vnd.apple.mpegurl',
    ]);

});

Route::get('/stream-ts/{folder}/{filename}', function ($folder, $filename) {
    // Đường dẫn tới file .ts trên NAS
    $filePath = "videos_hls/{$folder}/{$filename}";

    // Kiểm tra file tồn tại
    if (!Storage::disk('nas')->exists($filePath)) {
        return response()->json(['error' => 'File not found'], 404);
    }

    // Đọc nội dung file .ts
    $content = Storage::disk('nas')->get($filePath);

    // Trả nội dung file .ts
    return response($content, 200, [
        'Content-Type' => 'video/mp2t', // MIME type cho file .ts
    ]);
});
Route::get('/player/{folder}', function ($folder) {
    return view('player', ['folder' => $folder]);
});



