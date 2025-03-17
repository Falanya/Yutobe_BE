<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Redis;

class AdminController extends Controller
{
    public function index()
    {
        $videos = Video::orderBy('created_at', 'desc')->get();
        $queueCount = Redis::llen('queues:default'); // Lấy số job chờ xử lý trong Redis

        return view('admin.dashboard', compact('videos', 'queueCount'));
    }

    public function deleteVideo($uuid)
    {
        $video = Video::where('uuid', $uuid)->first();

        if (!$video) {
            return redirect()->route('admin.dashboard')->with('error', 'Video không tồn tại.');
        }

        // Xóa file video
        @unlink(public_path($video->file_path));
        @unlink(public_path($video->hls_path));
        @rmdir(dirname(public_path($video->hls_path)));

        $video->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Video đã được xóa thành công.');
    }

}
