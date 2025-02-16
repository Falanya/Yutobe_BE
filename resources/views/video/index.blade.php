<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách video</title>
</head>
<body>
    <h1>Danh sách video trong thư mục ZABBIX</h1>

    <ul>

        @foreach($mp4Files as $file)
            <li>
                <!-- Hiển thị tên file -->
                {{ basename($file) }}

                <!-- Trình phát video cho mỗi file -->
                <video width="320" height="240" controls>
                    <source src="{{ Storage::disk('ftp')->url($file) }}" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ video.
                </video>
            </li>
        @endforeach
    </ul>
</body>
</html>
