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
 * @time 21:14
 */

namespace App\Contracts\TrackingMore;

interface TrackingsInterface
{
    /**
     * Create a tracking.
     */
    public function createTracking(array $params = []): mixed;

    /**
     * Get tracking results of multiple trackings.
     */
    public function getTrackingResults(array $params = []): mixed;

    /**
     * Create multiple trackings (Max. 40 tracking numbers create in one call).
     */
    public function batchCreateTrackings(array $params = []): mixed;

    /**
     * Update a tracking by ID.
     */
    public function updateTrackingByID(string $idString = '', array $params = []): mixed;

    /**
     * Delete a tracking by ID.
     */
    public function deleteTrackingByID(string $idString = ''): mixed;

    /**
     * Retrack expired tracking by ID.
     */
    public function retrackTrackingByID(string $idString = ''): mixed;
}
