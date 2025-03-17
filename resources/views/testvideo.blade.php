<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://vjs.zencdn.net/8.0.0/video-js.css" rel="stylesheet">
    <script src="https://vjs.zencdn.net/8.0.0/video.min.js"></script>
</head>
<body>
    <video
        id="my-video"
        class="video-js vjs-default-skin"
        controls
        preload="auto"
        width="600"
        height="400"
        data-setup='{}'>
        <source src="{{ $videoPath }}" type="application/x-mpegURL">
    </video>
</body>
</html>
