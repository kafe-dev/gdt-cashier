<?php

namespace App\Console\Commands;

use App\Facades\TrackingMore;
use App\Models\OrderTracking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Trackingmore\TrackingMoreException;

class UpdateTrackingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tracking status from Tracking More and update to DB.';

    /**
     * Execute the console command.
     *
     * @throws TrackingMoreException
     */
    public function handle(): void
    {
        $trackings = DB::table('order_tracking')
            ->where('type', OrderTracking::TYPE_OPEN)
            ->get();

        foreach ($trackings as $tracking) {
            $courierCode = $tracking->courier_code;
            $trackingNumber = $tracking->tracking_number;

            if (is_null($courierCode) && ! is_null($trackingNumber)) {
                $response = TrackingMore::courier()->detect(['tracking_number' => $trackingNumber]);

                if (! empty($response)) {
                    $courierCode = $response['data'][0]['courier_code'];

                    DB::table('order_tracking')
                        ->where('id', $tracking->id)
                        ->update(['courier_code' => $courierCode]);
                }
            }

            if (! empty($courierCode)) {
                $response = TrackingMore::tracking()->getTrackingResults([
                    'tracking_numbers' => $trackingNumber,
                    'courier_code' => $courierCode,
                ]);

                if (empty($response['data'])) {
                    $response = TrackingMore::tracking()->createTracking([
                        'tracking_number' => $trackingNumber,
                        'courier_code' => $courierCode,
                    ]);

                    if (! empty($response['data']['delivery_status'])) {
                        $trackingData = $response['data'];
                        $newStatus = $trackingData['delivery_status'] ?? $tracking->tracking_status;

                        if ($newStatus !== $tracking->tracking_status) {
                            DB::table('order_tracking')
                                ->where('id', $tracking->id)
                                ->update([
                                    'tracking_status' => $newStatus,
                                    'tracking_data' => json_encode($trackingData),
                                    'last_checked_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                        }
                    }
                }

                if (! empty($response['data'][0]['delivery_status'])) {
                    $trackingData = $response['data'][0];
                    $newStatus = $trackingData['delivery_status'] ?? $tracking->tracking_status;

                    if ($newStatus !== $tracking->tracking_status) {
                        DB::table('order_tracking')
                            ->where('id', $tracking->id)
                            ->update([
                                'tracking_status' => $newStatus,
                                'tracking_data' => json_encode($trackingData),
                                'last_checked_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                    }
                }
            }
        }

        $this->info('Tracking statuses have been updated.');
    }
}
