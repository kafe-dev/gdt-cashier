<?php

declare(strict_types=1);

namespace App\Services\TrackingMore\Requests;

use Trackingmore\TrackingMoreException;
use TrackingMore\Trackings;

/**
 * Class Tracking.
 *
 * This class is the presentation for tracking requests.
 */
class Tracking
{

    /**
     * @var Trackings $trackings Instance of the Trackings class
     */
    private Trackings $trackings;

    /**
     * Constructor.
     *
     * @param  Trackings  $trackings
     */
    public function __construct(Trackings $trackings) {
        $this->trackings = $trackings;
    }

    /**
     * Create a new tracking.
     *
     * @param  array  $params
     *
     * @return array
     * @throws TrackingMoreException
     */
    public function createTracking(array $params = []): array
    {
        return $this->trackings->createTracking($params);
    }

    /**
     * Get tracking results.
     *
     * @param array $params
     *
     * @return array
     */
    public function getTrackingResults(array $params = []): array
    {
        return $this->trackings->getTrackingResults($params);
    }

    /**
     * Create multiple trackings (Max. 40 tracking numbers create in one call).
     *
     * @param  array  $params
     *
     * @return array
     * @throws TrackingMoreException
     */
    public function batchCreateTrackings(array $params = []): array
    {
        return $this->trackings->batchCreateTrackings($params);
    }

    /**
     * Update a tracking by ID.
     *
     * @param string $idString
     * @param array $params
     *
     * @return array
     * @throws TrackingMoreException
     */
    public function updateTrackingByID(string $idString = '', array $params = []): array
    {
        return $this->trackings->updateTrackingByID($idString, $params);
    }

    /**
     * Delete a tracking by ID.
     *
     * @param string $idString
     *
     * @return array
     * @throws TrackingMoreException
     */
    public function deleteTrackingByID(string $idString = ''): array
    {
        return $this->trackings->deleteTrackingByID($idString);
    }

    /**
     * Retrack expired tracking by ID.
     *
     * @param string $idString
     *
     * @return array
     * @throws TrackingMoreException
     */
    public function retrackTrackingByID(string $idString = ''): array
    {
        return $this->trackings->retrackTrackingByID($idString);
    }
}
