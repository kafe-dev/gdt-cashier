<?php
/**
 * @project gdt-cashier
 * @author hoepjhsha
 * @email hiepnguyen3624@gmail.com
 * @date 24/01/2025
 * @time 21:38
 */

namespace App\Services\TrackingMore;

use App\Contracts\TrackingMore\CouriersInterface;

class Couriers implements CouriersInterface
{

    use Request;

    private string $apiModule = 'couriers';

    /**
     * @inheritDoc
     * @throws TrackingMoreException
     */
    public function getAllCouriers(): mixed
    {
        $this->apiPath = 'all';
        return $this->sendApiRequest();
    }

    /**
     * @inheritDoc
     * @throws TrackingMoreException
     */
    public function detect(array $params = []): mixed
    {
        if (empty($params['tracking_number'])) {
            throw new TrackingMoreException(ErrorMessages::ErrMissingTrackingNumber);
        }
        $this->apiPath = 'detect';
        return $this->sendApiRequest($params,'POST');
    }
}
