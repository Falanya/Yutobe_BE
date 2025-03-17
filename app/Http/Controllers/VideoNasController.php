<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class VideoNasController extends Controller
{
    public $video;

    // Liệt kê các tệp trong thư mục FTP đã chọn và trả về đường dẫn video HLS
    public function listFiles()
    {
        // Dùng disk 'ftp' đã cấu hình trong config
        $disk = Storage::disk('ftp');

        $directoryPath = '/Movie/SONIX';  // Thư mục chứa video (điều chỉnh theo nhu cầu)

        // Lấy danh sách các tệp trong thư mục
        $files = $disk->files($directoryPath);

        // Log tất cả các tệp trong thư mục
        Log::info('Danh sách các tệp trong thư mục FTP:', $files);

        // Tìm kiếm tệp .m3u8 (playlist video HLS)
        $videoPlaylist = null;
        foreach ($files as $file) {
            if (strpos($file, '.m3u8') !== false) {
                $videoPlaylist = $file;  // Lưu lại tệp .m3u8 đầu tiên
                break;
            }
        }

        // Nếu tìm thấy tệp .m3u8, trả về đường dẫn video HLS
        if ($videoPlaylist) {
            $videoUrl = Storage::disk('ftp')->url($videoPlaylist);  // Lấy URL cho video HLS
            Log::info('Video HLS playlist: ' . $videoUrl);
        } else {
            $videoUrl = null;
            Log::info('Không tìm thấy tệp video HLS trong thư mục.');
        }

        // Trả về danh sách tệp và video_url cho frontend
        return response()->json([
            'message' => $videoUrl ? 'Video HLS playlist tìm thấy!' : 'Không tìm thấy video HLS.',
            'files' => $files,
            'video_url' => $videoUrl, // Đường dẫn video HLS (nếu có)
        ]);
    }

    public function testVideo()
    {
        $videoPath = Storage::disk('ftp')->url('Movie/SONIX/output.m3u8');

        return view('testvideo', compact('videoPath'));
    }

    public function streamVideo($filename)
    {
        // Kiểm tra sự tồn tại của file trên FTP
        $filePath = '/videos_hls/yournamefull/' . $filename; // Đảm bảo đường dẫn đầy đủ
        // dd($filePath);
        if (Storage::disk('ftp')->exists($filePath)) {
            // Lấy nội dung của file video
            $file = Storage::disk('ftp')->get($filePath);
            $mimeType = Storage::disk('ftp')->mimeType($filePath);

            // Trả về video dưới dạng response với header đúng để phát video
            return Response::make($file, 200, [
                'Content-Type' => $mimeType, // Đảm bảo Content-Type đúng với loại video
                'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
                'Content-Length' => strlen($file),
            ]);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
}
