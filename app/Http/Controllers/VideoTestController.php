<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class VideoTestController extends Controller
{
    /**
     * Lấy video từ FTP và trả về cho người dùng.
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function getVideo($filename)
    {
        // Định nghĩa đường dẫn tới file video trong FTP
        $filePath = "READ_UDEMY/ZABBIX/{$filename}";  // Đảm bảo đường dẫn đúng với thư mục FTP

        // Kiểm tra xem file có tồn tại trong FTP hay không
        if (Storage::disk('ftp')->exists($filePath)) {
            // Trả về file video dưới dạng phản hồi với kiểu dữ liệu là video/mp4
            return response()->file(Storage::disk('ftp')->path($filePath), [
                'Content-Type' => 'video/mp4',  // Đảm bảo định dạng video là mp4
            ]);
        } else {
            // Nếu file không tồn tại, trả về lỗi 404
            return response("File not found", 404);
        }
    }
}
