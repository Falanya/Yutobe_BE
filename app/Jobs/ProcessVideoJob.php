<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoPath;
    protected $videoUuid;

    public function __construct($videoPath, $videoUuid)
    {
        $this->videoPath = $videoPath;
        $this->videoUuid = $videoUuid;
    }

    public function handle()
    {
        $video = Video::where('uuid', $this->videoUuid)->first();
        if (!$video) {
            Log::error("Video không tồn tại: " . $this->videoUuid);
            return;
        }

        $outputDir = public_path("/videos_hls/{$this->videoUuid}");
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $outputPlaylist = "{$outputDir}/{$this->videoUuid}.m3u8";
        $outputSegment = "{$outputDir}/segment_%03d.ts";

        // 🔹 Chạy FFmpeg
        $command = "ffmpeg -i {$this->videoPath} -vf scale=1280:720 -profile:v baseline -level 3.0 -start_number 0 -hls_time 10 -hls_list_size 0 -hls_segment_filename {$outputSegment} -f hls {$outputPlaylist} > /dev/null 2>&1";
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            // 🔹 Cập nhật trạng thái video
            $video->update(['status' => 'completed']);
            Log::info("Xử lý video thành công: " . $this->videoUuid);
        } else {
            $video->update(['status' => 'failed']);
            Log::error("Lỗi xử lý video: " . $this->videoUuid);
        }
    }
}
