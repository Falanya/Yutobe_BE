<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function playlistVideo(){
        $fruits_array = ['fruit 1' => 'Apple','fruit 2' => 'Banana','fruit 3' => 'Melon','fruit 4' => 'Coconut','fruit 5' => 'Berry'];
        $fruits_string = 'Apple,Banana,Melon,Coconut,Berry';
        $array = explode(',', $fruits_string);
        $array_2 = [];
        for ($i = 0; $i < sizeof($array); $i++){
            $array_2 += ['fruit ' . $i + 1 => $array[$i]];
        }

        // print_r(array_values($array));
        print_r($fruits_array);
    }
}
