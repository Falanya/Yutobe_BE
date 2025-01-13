<?php

namespace App\Http\Controllers\API\Video;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadVideoController extends Controller
{
    public $disks = 'nas';
    public $directory_raw = 'videos_raw';
    public $directory_convert = 'videos_converted';

    public function __construct(){

    }

    public function uploadVideo(VideoRequest $request){
        $auth = auth()->user()->id;

        $namePrefix = uniqid();
        $videoName = $namePrefix . '.' . $request->video->getClientOriginalExtension();
        $thumbnailName = $namePrefix. '.' . $request->thumbnail->getClientOriginalExtension();

        $this->saveTemporarily($request,$videoName);
        $this->saveThumbnail($request,$thumbnailName);

        $videoPath = public_path("/$this->directory_raw/$videoName");
        $this->convertVideo($videoPath,$namePrefix);

        $this->saveDB($request,$auth,$thumbnailName,$namePrefix);

        return response()->json([
            'value' => $request->file('video'),
            'pathvideo' => $videoPath
        ],ResponseEnum::OK);

    }

    private function saveTemporarily($request,$videoName){
        $this->makeDirectoryPublic($this->directory_raw);
        $request->video->move(public_path($this->directory_raw), $videoName);
    }

    private function saveThumbnail($request,$thumbnailName){
        $this->makeDirectoryPublic('thumbnails');
        $request->thumbnail->move(public_path('thumbnails'),$thumbnailName);
    }

    private function saveDB($request,$auth,$thumbnailName,$videoName){
        Video::create([
            'title' => $request->title,
            'thumbnail' => "https://huylab.click/thumbnails/$thumbnailName",
            'description' => $request->description,
            'user_id' => $auth,
            'slug' => $videoName,
            'status' => 'public',
            'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
        ]);
    }

    private function convertVideo($videoPath,$videoName){
        $outputDir = public_path("/videos_hls/$videoName");
        if(!file_exists($outputDir)){
            mkdir($outputDir,0777,true);
        }

        $outputPlaylist = $outputDir . "/$videoName.m3u8";
        $outputSegment = $outputDir . '/segment_%03d.ts';

        $command = "ffmpeg -i $videoPath -profile:v baseline -level 3.0 -start_number 0 -hls_time 10 -hls_list_size 0 -hls_segment_filename $outputSegment -f hls $outputPlaylist";

        exec($command . " 2>&1", $output, $returnVar);
    }

    private function makeDirectoryPublic($directory){
        if(!file_exists($directory)){
            mkdir(public_path($directory),0777,true);
        }
    }

    private function makeDirectoryNas($directory){
        $storage_nas = Storage::disk($this->disks);
        if($storage_nas->exists($directory)){
            $storage_nas->makeDirectory($directory);
        }
    }


}
