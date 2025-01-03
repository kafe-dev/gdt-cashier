<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Class BaseController.
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 *
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController
{
    /**
     * Base constructor.
     */
    public function __construct() {}
}
