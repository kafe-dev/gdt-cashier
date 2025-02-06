<?php

namespace App\Helpers;
use Carbon\Carbon;
use DateTime;

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
}
