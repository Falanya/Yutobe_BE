<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HLS Video Player</title>
    <!-- Thêm HLS.js -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>

    <h1>HLS Video Player</h1>
    <video id="video" controls>
        Your browser does not support HLS.
    </video>

    <script>
        // Lấy video_url từ API Laravel
        fetch('/api/ftp/list-files')  // Đảm bảo đây là đúng route API của bạn
            .then(response => response.json())
            .then(data => {
                const videoUrl = data.video_url;  // Nhận video URL từ API

                if (videoUrl) {
                    // Kiểm tra xem trình duyệt có hỗ trợ HLS.js hay không
                    const video = document.getElementById('video');
                    if (Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(videoUrl);  // Nạp video từ URL
                        hls.attachMedia(video);    // Gắn video với thẻ <video>
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        // Safari hoặc trình duyệt hỗ trợ HLS natively
                        video.src = videoUrl;
                    }
                } else {
                    console.error('Không tìm thấy video HLS!');
                }
            })
            .catch(error => console.error('Error fetching video URL:', error));
    </script>

</body>
</html>
