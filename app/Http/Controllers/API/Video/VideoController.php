<?php

namespace App\Http\Controllers\API\Video;

use App\Enums\ResponseEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Like_video;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VideoController extends Controller
{
    public function getVideoUrl($slug)
    {
        $video = new VideoResource(Video::where('slug', $slug)->where('verified', 1)->firstOrFail());
        // $video['url'] = "https://huylab.click/videos_hls/$slug/$slug.m3u8";
        if ($video) {
            return response()->json([
                'success' => true,
                'video' => $video,
            ], ResponseEnum::ACCEPTED);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Not found',
            ], ResponseEnum::NO_CONTENT);
        }
    }

    public function loadVideo($slug)
    {
        $filePath = public_path("videos_hls/$slug/$slug.m3u8");

        // Kiểm tra file có tồn tại không
        if (file_exists($filePath)) {
            // Lấy MIME type của file
            $mimeType = mime_content_type($filePath);

            // Trả về video dưới dạng response
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            ]);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }

        // if (Storage::disk('ftp')->exists($filePath)) {
        //     // Lấy nội dung của file video
        //     $file = Storage::disk('ftp')->get($filePath);
        //     $mimeType = Storage::disk('ftp')->mimeType($filePath);

        //     // Trả về video dưới dạng response với header đúng để phát video
        //     return Response::make($file, 200, [
        //         'Content-Type' => $mimeType, // Đảm bảo Content-Type đúng với loại video
        //         'Content-Disposition' => 'inline; filename="' . basename($slug) . '"',
        //         'Content-Length' => strlen($file),
        //     ]);
        // } else {
        //     return response()->json(['error' => 'File not found'], 404);
        // }
    }

    public function getAll()
    {
        $videos = VideoResource::collection(Video::where('verified', 1)->where('status', 'public')->orderBy('id', 'DESC')->get());
        if ($videos) {
            return response()->json([
                'videos' => $videos,
                'success' => true
            ], ResponseEnum::ACCEPTED);
        } else {
            return response()->json([
                'message' => 'Not found',
                'success' => false
            ], ResponseEnum::NO_CONTENT);
        }
    }

    public function videoSuggestInDetail($slug)
    {
        $video = Video::where('slug', $slug)->first();
        $videos = VideoResource::collection(Video::whereNotIn('id', [$video->id])->where('verified', 1)->inRandomOrder()->limit(10)->get());
        if ($videos) {
            return response()->json([
                'videos' => $videos,
                'success' => true
            ], ResponseEnum::ACCEPTED);
        } else {
            return response()->json([
                'message' => 'Not found',
                'success' => false
            ], ResponseEnum::NO_CONTENT);
        }
    }

    public function addView($slug)
    {
        $video = Video::where('slug', $slug)->firstOrFail();
        if ($video) {
            $video->increment('view');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        }
    }

    public function likeVideo($slug)
    {
        $video = Video::where('slug', $slug)->first();
        $auth = auth()->user();
        $checkLike = Like_video::where([
            'user_id' => $auth->id,
            "video_id" => $video->id,
        ])->first();
        if ($checkLike) {
            return response()->json([
                'success' => false,
                'message' => 'Liked',
            ]);
        } else {
            $like = Like_video::create([
                'user_id' => $auth->id,
                'video_id' => $video->id,
            ]);
            if ($like) {
                return response()->json([
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ]);
            }
        }

    }

    public function checkLike($slug)
    {
        $video = Video::where('slug', $slug)->first();
        if ($video) {
            $auth = auth()->user()->id;
            $check = Like_video::where('user_id', $auth)->where('video_id', $video->id)->first();
            if ($check) {
                return response()->json([
                    'success' => true,
                    'like_status' => true,
                ], ResponseEnum::ACCEPTED);
            } else {
                return response()->json([
                    'success' => false,
                    'like_status' => false,
                ], ResponseEnum::NO_CONTENT);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ], ResponseEnum::NO_CONTENT);
        }
    }

    public function dislikeVideo($slug)
    {
        $auth = auth()->user()->id;
        $video = Video::where('slug', $slug)->first();
        if ($video) {
            $check = Like_video::where('video_id', $video->id)->where('user_id', $auth)->first();
            if ($check) {
                $delete = $check->delete();
                if ($delete) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Like canceled',
                    ], ResponseEnum::ACCEPTED);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Some errors, please try again'
                    ], ResponseEnum::BADREQUEST);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Please try again'
                ], ResponseEnum::NOTFOUND);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ], ResponseEnum::NO_CONTENT);
        }
    }

    public function getVideoLike()
    {
        $auth = auth()->user();
        $like_id = Like_video::where('user_id', $auth->id)->orderBy('id', 'DESC')->get('video_id');
        if ($like_id) {
            $videos_id = [];
            $videos = [];
            foreach ($like_id as $key => $video) {
                $videos_id[] = $video->video_id;
            }
            for ($i = 0; $i < count($videos_id); $i++) {
                $videos[] = new VideoResource(Video::where('id', $videos_id[$i])->where('verified', 1)->firstOrFail());
            }
            // $videos = VideoResource::collection();
            return response()->json([
                'videos' => $videos,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ]);
        }

    }

    public function deleteVideoLike($id)
    {
        $auth = auth()->user();
        $find = Like_video::where('user_id', $auth->id)->where('video_id', $id)->first();
        if ($find) {
            $find->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted video',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ]);
        }

    }

    public function deleteAllVideoLike()
    {
        $auth = auth()->user();
        $find = Like_video::where('user_id', $auth->id);
        if ($find) {
            $find->delete();
            return response()->json([
                'success' => true,
                'message' => 'Deleted video'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ]);
        }

    }

    public function getVideoUser()
    {
        $auth = auth()->user();
        $videos = VideoResource::collection(Video::where('user_id', $auth->id)->where('verified', 1)->orderBy('id', 'DESC')->get());
        if ($videos) {
            return response()->json([
                'success' => true,
                'videos' => $videos,
            ], ResponseEnum::ACCEPTED);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ], ResponseEnum::BADREQUEST);
        }
    }

    public function getVideoUserAll()
    {
        $auth = auth()->user();
        $videos = VideoResource::collection(Video::where('user_id', $auth->id)->orderBy('id', 'DESC')->get());
        if ($videos) {
            return response()->json([
                'success' => true,
                'videos' => $videos,
            ], ResponseEnum::ACCEPTED);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found'
            ], ResponseEnum::BADREQUEST);
        }
    }

    public function editVideo(Request $request, $slug)
    {
        $auth = auth()->user();
        $find = Video::where('slug', $slug)->where('user_id', $auth->id)->first();
        if ($find) {
            if ($find->title != $request->title) {
                $find->title = $request->title;
            }
            if ($find->description != $request->description) {
                $find->description = $request->description;
            }
            if ($find->status != $request->status) {
                $find->status = $request->status;
            }
            $find->save();
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ]);
        }
    }

    public function searchVideo($keyword)
    {
        $search = trim($keyword);

        if (!empty($keyword)) {
            $query = Video::where('status', 'public')
                ->where('verified', 1)
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->orderBy('view', 'DESC')
                ->limit(10)
                ->get();

            $videos = VideoResource::collection($query);
            return response()->json([
                'videos' => $videos,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid data sent',
        ], 400);
    }

}
