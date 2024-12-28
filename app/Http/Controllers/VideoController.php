<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Chuyển video sang định dạng HLS (HTTP Live Streaming).
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040',
        ]);

        if ($request->hasFile('video')) {
            $this->ensureDirectoryExists(public_path('videos_raw'));

            $videoNamePrefix = time();
            $videoName = $videoNamePrefix . '.' . $request->video->getClientOriginalExtension();

            if (!$request->video->move(public_path('/videos_raw'), $videoName)) {
                return response()->json(['message' => 'Không thể upload file video.'], 500);
            }

            $videoPath = public_path('/videos_raw/' . $videoName);
            return $this->convertVideo($videoPath, $videoNamePrefix); // Trả về phản hồi từ hàm convertVideo
        } else {
            return response()->json([
                'message' => 'File không tồn tại.',
                'status' => 404,
            ]);
        }
    }

    private function convertVideo($videoPath, $videoNamePrefix)
    {
        $outputDir = public_path("/videos_hls/$videoNamePrefix");
        $this->ensureDirectoryExists($outputDir);

        $outputPlaylist = $outputDir . "/$videoNamePrefix.m3u8";
        $outputSegment = $outputDir . '/segment_%03d.ts';

        // Lệnh FFmpeg để tạo HLS và chia thành nhiều file .ts
        $command = "ffmpeg -i $videoPath -profile:v baseline -level 3.0 -start_number 0 -hls_time 10 -hls_list_size 0 -hls_segment_filename $outputSegment -f hls $outputPlaylist";

        // Thực thi lệnh FFmpeg
        exec($command . " 2>&1", $output, $returnVar);

        if ($returnVar === 0) {
            return response()->json([
                'message' => 'Video đã được chuyển đổi thành công sang HLS.',
                'playlist' => "/videos_hls/$videoNamePrefix/$videoNamePrefix.m3u8", // Trả về URL playlist
            ]);
        } else {
            // Ghi log lỗi nếu lệnh thất bại
            // \Log::error("FFmpeg lỗi khi chuyển đổi video:", $output);

            return response()->json([
                'message' => 'Có lỗi khi chuyển đổi video.',
                'error_details' => $output, // Trả về chi tiết lỗi
            ], 500);
        }
    }

    private function ensureDirectoryExists($directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }
}
