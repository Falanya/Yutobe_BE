<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class UploadVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $videoFile;
    public $videoName;
    public $thumbnailName;
    public $auth;
    public $requestData;
    public $directoryRaw = 'videos_raw';

    /**
     * Create a new job instance.
     */
    public function __construct($videoFile, $videoName, $thumbnailName, $auth, $requestData)
    {
        $this->videoFile = $videoFile;
        $this->videoName = $videoName;
        $this->thumbnailName = $thumbnailName;
        $this->auth = $auth;
        $this->requestData = $requestData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Bước 1: Lưu file tạm thời
        $tempPath = $this->saveTemporarily();

        // Bước 2: Chuyển đổi video nếu cần
        $this->convertVideo($tempPath);

        // Bước 3: Lưu vào database
        $this->saveToDatabase();
    }

    private function saveTemporarily()
    {
        // Tạo thư mục nếu chưa tồn tại
        $tempDirectory = storage_path("app/public/{$this->directoryRaw}");
        if (!file_exists($tempDirectory)) {
            mkdir($tempDirectory, 0777, true);
        }

        // Lưu file tạm vào thư mục
        $tempPath = "{$tempDirectory}/{$this->videoName}";
        file_put_contents($tempPath, file_get_contents($this->videoFile->getRealPath()));

        return $tempPath;
    }

    private function convertVideo($videoPath)
    {
        $outputDir = public_path("videos_hls/{$this->videoName}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputPlaylist = $outputDir . "/{$this->videoName}.m3u8";
        $outputSegment = $outputDir . '/segment_%03d.ts';

        $command = "ffmpeg -i $videoPath -profile:v baseline -level 3.0 -start_number 0 -hls_time 10 -hls_list_size 0 -hls_segment_filename $outputSegment -f hls $outputPlaylist";

        exec($command . " 2>&1", $output, $returnVar);
    }

    private function saveToDatabase()
    {
        Video::create([
            'title' => $this->requestData['title'],
            'thumbnail' => $this->thumbnailName,
            'description' => $this->requestData['description'],
            'user_id' => $this->auth,
            'slug' => $this->videoName,
            'status' => 'public',
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ]);
    }
}
