<?php

namespace App\Http\Controllers\API\Movie;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function __construct(){

    }

    public function getAllMovie(){
        $movies = MovieResource::collection(Movie::where('status','public')->orderBy('id','DESC')->get());
        if($movies){
            return response()->json([
                'success' => true,
                'movies' => $movies,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Not found',
            ]);
        }
    }

    public function getMovie($slug){
        $find = Movie::where('slug',$slug)->first();
        if($find){
            $movie = new MovieResource($find);
            return response()->json([
                'success' => true,
                'movie' => $movie,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        }
    }

    public function getFileMovie($slug,$filename){
        $filePath = "/Movie/$slug/$filename";
        $filePathTS = "/Movie/$slug/$filename";
        if (Storage::disk('ftp')->exists($filePath)) {
            $file = Storage::disk('ftp')->get($filePath);
            $mimeType = Storage::disk('ftp')->mimeType($filePath);

            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($slug) . '"',
                'Content-Length' => strlen($file),
            ]);
        }else if(Storage::disk('ftp')->exists($filePathTS)){
            $file = Storage::disk('ftp')->get($filePathTS);
            $mimeType = Storage::disk('ftp')->mimeType($filePathTS);

            return Response::make($file, 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . basename($slug) . '"',
                'Content-Length' => strlen($file),
            ]);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function addView($slug){
        $movie = Movie::where('slug',$slug)->first();
        if($movie){
            $movie->view += 1;
            $movie->save();
            return response()->json([
                'success' => true,
                'message' => 'added view',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Movie not found',
            ]);
        }
    }

    public function getMovieAdmin(){
        $auth = auth()->user();
        if($auth->isAdmin == true){
            $movies = MovieResource::collection(Movie::orderBy('id','DESC')->get());
            return response()->json([
                'success' => true,
                'movies' => $movies,
            ]);
        }else{
            return response()->json([
                'message' => 'please try again',
                'success' => false
            ]);
        }
    }
}
