<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 24/01/2025
 * @time 21:13
 */

namespace App\Contracts\TrackingMore;

interface CouriersInterface
{
    /**
     * Return a list of all supported couriers.
     * @return mixed
     */
    public function getAllCouriers(): mixed;

    /**
     * Return a list of matched couriers based on submitted tracking number.
     * @param array $params
     * @return mixed
     */
    public function detect(array $params = []): mixed;
}
