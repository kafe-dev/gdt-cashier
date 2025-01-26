<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\TrackingMore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class Tracking.
 *
 * This controller is responsible for managing tracking-related operations.
 */
class Tracking extends BaseController
{
    /**
     * Action `index`.
     */
    public function index()
    {
        try {
            // Create a single tracking
            TrackingMore::tracking()->createTracking(['tracking_number' => '92144903627188110001327221', 'courier_code' => 'usps']);

            $result = TrackingMore::tracking()->getTrackingResults();
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return view('tracking.index');
    }

    /**
     * Action `show`.
     *
     * @param  int|string  $id  The tracking ID
     */
    public function show(int|string $id): View
    {
        return view('tracking.show');
    }

    /**
     * Action `delete`.
     *
     * @param  int|string  $id  The tracking ID
     * @param  Request  $request  Illuminate request object
     */
    public function delete(int|string $id, Request $request): RedirectResponse
    {
        //
    }
}
