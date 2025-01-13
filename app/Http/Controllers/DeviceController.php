<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class DeviceController extends Controller
{
    public function getDeviceInfo(Request $request)
    {
        $agent = new Agent();

        // Lấy thông tin chi tiết từ User-Agent
        $ip = $request->ip();
        $browser = $agent->browser();
        $platform = $agent->platform();
        $device = $agent->device();
        $isDesktop = $agent->isDesktop();
        $isMobile = $agent->isMobile();
        $isTablet = $agent->isTablet();
        $userAgent = $request->header('User-Agent');

        // Trả về dữ liệu dạng JSON
        return response()->json([
            'ip' => $ip,
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
            'is_desktop' => $isDesktop,
            'is_mobile' => $isMobile,
            'is_tablet' => $isTablet,
            'user_agent' => $userAgent,
        ]);
    }
}
