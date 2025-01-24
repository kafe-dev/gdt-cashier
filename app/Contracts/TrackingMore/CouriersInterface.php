<?php

/**
 * @project gdt-cashier
 *
 * @author hoepjhsha
 *
 * @email hiepnguyen3624@gmail.com
 *
 * @date 24/01/2025
 *
 * @time 21:13
 */

namespace App\Contracts\TrackingMore;

interface CouriersInterface
{
    /**
     * Return a list of all supported couriers.
     */
    public function getAllCouriers(): mixed;

    /**
     * Return a list of matched couriers based on submitted tracking number.
     */
    public function detect(array $params = []): mixed;
}
