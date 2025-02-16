<!DOCTYPE html>
<html>
<head>
    <title>Danh sách Video HLS</title>
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet">
</head>
<body>
    <h1>Danh sách Video HLS</h1>
    <ul>
        @foreach ($videos as $video)
            <li>
                <h3>{{ $video['name'] }}</h3>
                <video id="videoPlayer" class="video-js vjs-default-skin" controls width="600" height="400">
                    <source src="{{ $video['url'] }}" type="application/x-mpegURL">
                </video>
            </li>
        @endforeach
    </ul>
    <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
</body>
</html>
