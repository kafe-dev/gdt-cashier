<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\View\View;
use ReflectionClass;
use ReflectionException;

/**
 * Class NotificationWidget.
 *
 * This class is responsible for generating the notification widget.
 */
class NotificationWidget
{
    /**
     * Renders the notification widget.
     *
     * @param string $class Fully qualified class name of the model
     * @param string $attribute Name of the attribute to filter on
     * @param string|null $value Value to filter on
     *
     * @return View
     * @throws ReflectionException
     */
    public static function render(string $class, string $attribute, null|string $value): View
    {
        $model = (new ReflectionClass($class))->newInstance();

        $total = $model->where($attribute, $value)->count();

        if ($total > 0) {
            $style = 'display: block;';
        } else {
            $style = 'display: none;';
        }

        return view('_widgets.notification', ['total' => $total, 'style' => $style]);
    }
}
