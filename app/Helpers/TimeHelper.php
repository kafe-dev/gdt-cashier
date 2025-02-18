<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;

class TimeHelper {

    /**
     * @param string $time Định dạng 2025-01-01T00:00:00Z
     *
     * @return string
     * @throws \DateMalformedStringException
     */
    public static function getFormattedStartTime($time): string {
        $dateTime = new DateTime($time);
        return $dateTime->format('Y-m-d\TH:i:s.000\Z'); // Định dạng chuẩn PayPal
    }

    /**
     * @param string $datetime Định dạng 2025-01-09T23:44:07+0000
     *
     * @throws \DateMalformedStringException
     */
    public static function convertDateTime($dateTime): string {
        // Tạo đối tượng DateTime từ chuỗi
        $date = new DateTime($dateTime);
        // Chuyển đổi định dạng
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Tạo chuỗi định dạng ngày giờ cho Paypal API.
     * Nhận chuỗi timestamp theo định dạng 2025-01-10T05:54:25.000Z
     * Trả về mảng có 2 phần tử: start và end
     * - start: chuỗi định dạng ngày giờ bắt đầu của ngày, ví dụ 2025-01-10T00:00:00.000Z
     * - end: chuỗi định dạng ngày giờ kết thúc của ngày, ví dụ 2025-01-10T23:59:59.000Z
     *
     * @param string $timestamp
     *
     * @return array
     */
    public static function getStartAndEndOfDay(string $timestamp): array {
        $date       = Carbon::parse($timestamp)->setTimezone('UTC'); // Chuyển đổi về múi giờ UTC nếu cần
        $startOfDay = $date->copy()->startOfDay()->format('Y-m-d\TH:i:s.000\Z');
        $endOfDay   = $date->copy()->endOfDay()->format('Y-m-d\TH:i:s.000\Z');
        return [
            'start' => $startOfDay,
            'end'   => $endOfDay,
        ];
    }

    /**
     * Tính toán và trả về chuỗi thể hiện khoảng thời gian cách biệt từ lúc $timestamp đến bây giờ.
     *
     * Ví dụ: $timestamp = 2025-01-16T12:59:53.247Z
     *        trả về 5 days ago
     *
     * @param string $timestamp
     *
     * @return string
     * @throws \DateMalformedStringException
     */
    public static function getTimeAgo($timestamp): ?string {
        $datetime = new DateTime($timestamp);
        $now      = new DateTime();
        $diff     = $now->getTimestamp() - $datetime->getTimestamp();
        if ($diff < 60) {
            return "Just now";
        } elseif ($diff < 3600) {
            return floor($diff / 60) . " min ago";
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . " hours ago";
        } else {
            return floor($diff / 86400) . " days ago";
        }
    }

    /**
     * Tính toán và trả về chuỗi thể hiện ngày bắt đầu của ngày $date (hoặc ngày hiện tại) trong múi giờ $timezone.
     * Chuỗi trả về được định dạng theo ISO 8601
     *
     * Ví dụ: $date = 2025-01-16T12:59:53.247Z, $timezone = 'Asia/Ho_Chi_Minh'
     *        trả về 2025-01-16T00:00:00.000Z
     *
     * @param string $date
     * @param string $timezone
     *
     * @return string
     * @throws \DateMalformedStringException
     * @throws \DateInvalidTimeZoneException
     */
    public static function getStartOfDayISO($date = 'now', $timezone = 'Asia/Ho_Chi_Minh'): string {
        $dateTime = new DateTime($date, new DateTimeZone($timezone));
        $dateTime->setTime(0, 0, 0, 0); // Đặt thời gian về 00:00:00.000
        $dateTime->setTimezone(new DateTimeZone('UTC')); // Chuyển về UTC

        return $dateTime->format('Y-m-d\TH:i:s.v\Z');
    }
}
