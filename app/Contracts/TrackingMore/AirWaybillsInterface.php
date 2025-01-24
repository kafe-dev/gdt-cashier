<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 24/01/2025
 * @time 21:12
 */

namespace App\Contracts\TrackingMore;

interface AirWaybillsInterface
{
    /**
     * Create an air waybill.
     * @param array $params
     * @return mixed
     */
    public function createAnAirWayBill(array $params = []): mixed;
}
