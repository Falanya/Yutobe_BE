<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideoEventController extends Controller
{
    public function store(Request $request)
    {
        // Xác nhận dữ liệu yêu cầu
        $validated = $request->validate([
            'event' => 'required|string',
            'video_title' => 'required|string',
            'video_percent' => 'required|numeric',
            'video_current_time' => 'required|numeric',
            'video_duration' => 'required|numeric',
            'video_url' => 'required|string',
            'video_provider' => 'required|string',
        ]);

        // Ghi dữ liệu vào log để kiểm tra
        Log::info('Received video event:', $validated);

        // Lưu sự kiện vào cơ sở dữ liệu (nếu cần)
        // Ví dụ: VideoEvent::create($validated);

        return response()->json(['message' => 'Event successfully received']);
    }
}
