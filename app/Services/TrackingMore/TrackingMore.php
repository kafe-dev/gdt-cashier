<?php

declare(strict_types=1);

namespace App\Services\TrackingMore;

use App\Services\TrackingMore\Requests\Courier;
use App\Services\TrackingMore\Requests\Tracking;

/**
 * Class TrackingMore.
 *
 * This is the main service for TrackingMore API.
 */
class TrackingMore
{

    /**
     * @var Tracking $tracking Instance of Tracking class
     */
    protected Tracking $tracking;

    /**
     * @var Courier $courier Instance of Courier class
     */
    protected Courier $courier;

    /**
     * Constructor.
     *
     * @param  Tracking  $tracking
     * @param  Courier  $courier
     */
    public function __construct(Tracking $tracking, Courier $courier) {
        $this->tracking = $tracking;
        $this->courier = $courier;
    }

    /**
     * Get new instance of the Tracking class.
     *
     * @return Tracking
     */
    public function tracking(): Tracking {
        return $this->tracking;
    }

    /**
     * Get new instance of the Courier class.
     *
     * @return Courier
     */
    public function courier(): Courier {
        return $this->courier;
    }
}
