<!DOCTYPE html>
<html>
<head>
    <title>HLS Video Player</title>
    <!-- Thêm CSS của Video.js -->
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet">
</head>
<body>
    <h1>HLS Video Player</h1>
    <!-- Video.js player -->
    <video id="videoPlayer" class="video-js vjs-default-skin" controls width="800" height="450" crossorigin="anonymous">
        <source src="{{ url("/stream-m3u8/{$folder}") }}" type="application/x-mpegURL">
    </video>

    <!-- Thêm thư viện Video.js -->
    <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>

    <!-- Thêm HLS plugin nếu trình duyệt không hỗ trợ native HLS -->
    <script src="https://cdn.jsdelivr.net/npm/videojs-contrib-hls/dist/videojs-contrib-hls.min.js"></script>

    <script>
        var player = videojs('videoPlayer');
    </script>
</body>
</html>
