<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\TrackingMore\TrackingMoreException;
use App\Services\TrackingMore\Trackings;
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
    private Trackings $trackings;

    public function __construct()
    {
        parent::__construct();

        $this->trackings = new Trackings();
    }

    /**
     * Action `index`.
     * @throws TrackingMoreException
     */
    public function index()
    {
        $response = $this->trackings->createTracking([
            'tracking_number' => 'YT2500721403049042',
            'courier_code' => 'yunexpress',
        ]);

        $response2 = $this->trackings->getTrackingResults([
            'tracking_number'=>'YT2500721403049042',
            'courier_code'=>'yunexpress',
            'created_date_min'=>'2023-08-23T06:00:00+00:00',
            'created_date_max'=>'2023-09-05T07:20:42+00:00'
        ]);

        dd($response2);

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
