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
 * @time 21:26
 */

namespace App\Services\TrackingMore;

use App\Contracts\TrackingMore\AirWaybillsInterface;

class AirWaybills implements AirWaybillsInterface
{
    use Request;

    private string $apiModule;

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function createAnAirWayBill(array $params = []): mixed
    {
        if (empty($params['awb_number'])) {
            throw new TrackingMoreException(ErrorMessages::ErrMissingAwbNumber);
        }
        if (! preg_match('/^\d{3}[ -]?(\d{8})$/', $params['awb_number'])) {
            throw new TrackingMoreException(ErrorMessages::ErrInvalidAirWaybillFormat);
        }
        $this->apiPath = 'awb';

        return $this->sendApiRequest($params, 'POST');
    }
}
