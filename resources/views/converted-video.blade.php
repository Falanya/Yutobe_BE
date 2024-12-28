<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player</title>
    <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet">
    <style>
        /* Đảm bảo video chiếm đúng không gian và giữ tỷ lệ */
        .video-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto; /* Căn giữa video */
        }

        /* Đảm bảo tỷ lệ 16:9 cho video */
        .video-container::before {
            content: '';
            display: block;
            padding-top: 56.25%; /* Tỷ lệ 16:9, có thể thay đổi nếu muốn */
        }

        video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: black;
        }

        /* Thanh điều khiển */
        .vjs-control-bar {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        /* Căn chỉnh các nút điều khiển */
        .vjs-play-control,
        .vjs-replay-control,
        .vjs-forward-control {
            margin: 0 10px;
        }

        .vjs-button {
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 50%;
            padding: 5px;
            color: white !important;
        }

        .vjs-button:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body>
    <h1>Video Converter</h1>
    <div class="video-container">
        <video id="my_video_1" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid":true}'>
            <source src="{{ asset('/video/output/hls/playlist.m3u8') }}" type="application/x-mpegURL">
        </video>
    </div>

    <!-- Load Video.js scripts -->
    <script src="https://unpkg.com/video.js/dist/video.js"></script>
    <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
    <script>
        // Khởi tạo Video.js player
        var player = videojs('my_video_1', {
            controls: true,
            autoplay: false,
            preload: 'auto',
            fluid: true
        });
    </script>
</body>
</html>
