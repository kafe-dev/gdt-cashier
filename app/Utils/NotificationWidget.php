<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\View\View;

/**
 * Class NotificationWidget.
 *
 * This class is responsible for generating the notification widget.
 */
class NotificationWidget
{

    /**
     * Renders the notification widget.
     */
    public static function render(): View
    {
        return view('_widgets.notification', ['total' => 4]);
    }

}
