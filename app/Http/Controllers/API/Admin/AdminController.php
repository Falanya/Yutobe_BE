<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getMyAdmin(){
        $auth = auth()->user();
        if($auth){
            return response()->json([
                'success' => true,
                'user' => $auth,
            ]);
        }
        return response()->json([
            'success' => false,
            'user' => null,
        ]);
    }
}
