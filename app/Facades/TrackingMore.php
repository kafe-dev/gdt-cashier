<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\TrackingMore\Requests\Courier;
use App\Services\TrackingMore\Requests\Tracking;
use Illuminate\Support\Facades\Facade;
use App\Services\TrackingMore\TrackingMore as TrackingMoreService;

/**
 * Class TrackingMore.
 *
 * This facade provides the simple way to access the TrackingMore service.
 *
 * @method static Tracking tracking()
 * @method static Courier courier()
 */
class TrackingMore extends Facade
{

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return TrackingMoreService::class;
    }
}
