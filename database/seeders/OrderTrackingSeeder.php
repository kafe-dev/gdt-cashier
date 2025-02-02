<?php

namespace Database\Seeders;

use App\Models\OrderTracking;
use Illuminate\Database\Seeder;

class OrderTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderTracking::factory()->create([
            'tracking_number' => 'VR680019778YP',
            'courier_code' => 'yanwen'
        ]);
        OrderTracking::factory()->create([
            'tracking_number' => 'YWBZ2025010114',
            'courier_code' => 'ywbzexpress'
        ]);
        OrderTracking::factory()->create([
            'tracking_number' => 'YT2500721403049056',
            'courier_code' => 'yunexpress'
        ]);
    }
}
