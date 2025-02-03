<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::factory()->create([
            'user_id' => 1,
            'name' => 'Lux Print',
            'url' => 'https://luxprint.es',
            'description' => 'This is the demo WooCommerce store, using for testing purposes',
            'api_data' => json_encode([
                'consume_key' => 'LLGaCOVXdqoDehMv',
                'consume_secret' => 'ZcTADJdHqPxcX04s'
            ]),
            'status' => Store::STATUS_ACTIVE,
        ]);
    }
}
