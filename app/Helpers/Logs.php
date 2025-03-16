<?php

namespace App\Helpers;
class Logs {

    public static function create($file, $func, $line, $message, $logFile = 'app.log') {
        // Đường dẫn đầy đủ tới file log
        $logPath = storage_path('logs/' . $logFile);
        // Tạo nội dung log với timestamp
        $timestamp = date('Y-m-d H:i:s'); // Format thời gian: YYYY-MM-DD HH:MM:SS
        $logEntry  = "[{$timestamp}][{$file}][{$func}][{$line}]: {$message}" . PHP_EOL;
        // Ghi log vào file (thêm vào cuối file nếu đã tồn tại)
        file_put_contents($logPath, $logEntry, FILE_APPEND | LOCK_EX);
    }
}
