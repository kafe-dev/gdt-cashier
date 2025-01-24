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
 * @time 21:41
 */

namespace App\Services\TrackingMore;

use App\Contracts\TrackingMore\TrackingsInterface;

class Trackings implements TrackingsInterface
{
    use Request;

    private $apiModule = 'trackings';

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function createTracking(array $params = []): mixed
    {
        if (empty($params['tracking_number'])) {
            throw new TrackingMoreException(ErrorMessages::ErrMissingTrackingNumber);
        }
        if (empty($params['courier_code'])) {
            throw new TrackingMoreException(ErrorMessages::ErrMissingCourierCode);
        }
        $this->apiPath = 'create';

        return $this->sendApiRequest($params, 'POST');
    }

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function getTrackingResults(array $params = []): mixed
    {
        $paramsValue = http_build_query($params);
        $this->apiPath = "get?$paramsValue";

        return $this->sendApiRequest();
    }

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function batchCreateTrackings(array $params = []): mixed
    {
        if (count($params) > 40) {
            throw new TrackingMoreException(ErrorMessages::ErrMaxTrackingNumbersExceeded);
        }
        for ($i = 0; $i < count($params); $i++) {
            if (empty($params[$i]['tracking_number'])) {
                throw new TrackingMoreException(ErrorMessages::ErrMissingTrackingNumber);
            }
            if (empty($params[$i]['courier_code'])) {
                throw new TrackingMoreException(ErrorMessages::ErrMissingCourierCode);
            }
        }
        $this->apiPath = 'batch';

        return $this->sendApiRequest($params, 'POST');
    }

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function updateTrackingByID(string $idString = '', array $params = []): mixed
    {
        if (empty($idString)) {
            throw new TrackingMoreException(ErrorMessages::ErrEmptyId);
        }
        $this->apiPath = "update/$idString";

        return $this->sendApiRequest($params, 'PUT');
    }

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function deleteTrackingByID(string $idString = ''): mixed
    {
        if (empty($idString)) {
            throw new TrackingMoreException(ErrorMessages::ErrEmptyId);
        }
        $this->apiPath = "delete/$idString";

        return $this->sendApiRequest(null, 'DELETE');
    }

    /**
     * {@inheritDoc}
     *
     * @throws TrackingMoreException
     */
    public function retrackTrackingByID(string $idString = ''): mixed
    {
        if (empty($idString)) {
            throw new TrackingMoreException(ErrorMessages::ErrEmptyId);
        }
        $this->apiPath = "retrack/$idString";

        return $this->sendApiRequest(null, 'POST');
    }
}
