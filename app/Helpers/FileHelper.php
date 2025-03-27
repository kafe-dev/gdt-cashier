<?php

namespace App\Helpers;
class FileHelper {

    public static function getFileExtensionBy($file_name): string {
        return strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    }
}
