<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'], // Các route áp dụng
    'allowed_methods' => ['*'], // Tất cả phương thức
    'allowed_origins' => ['https://yutube.huylab.click'], // Chỉ định domain cụ thể
    'allowed_origins_patterns' => [], // Không sử dụng regex
    'allowed_headers' => ['Content-Type', 'Authorization'], // Header được phép
    'exposed_headers' => [],
    'max_age' => 3600, // Cache preflight
    'supports_credentials' => true, // Hỗ trợ cookies và thông tin xác thực

];
