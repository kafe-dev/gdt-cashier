<?php

declare(strict_types=1);

namespace App\Services\TrackingMore\Requests;

use TrackingMore\Couriers;
use Trackingmore\TrackingMoreException;

/**
 * Class Courier.
 *
 * This class is the presentation for courier requests.
 */
class Courier
{

    /**
     * @var Couriers $couriers Instance of Couriers class
     */
    private Couriers $couriers;

    /**
     * Constructor.
     *
     * @param  Couriers  $couriers
     */
    public function __construct(Couriers $couriers) {
        $this->couriers = $couriers;
    }

    /**
     * Return a list of all supported couriers.
     *
     * @return array
     */
    public function getAllCouriers(): array
    {
        return $this->couriers->getAllCouriers();
    }

    /**
     * Return a list of matched couriers based on submitted tracking number.
     *
     * @param array $params
     *
     * @return array
     * @throws TrackingMoreException
     */
    public function detect(array $params = []): array
    {
        return $this->couriers->detect($params);
    }
}
