<?php

namespace Database\Seeders;

use App\Models\Paygate;
use Illuminate\Database\Seeder;

class PaygateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paygate::factory()->create([
            'name' => 'Sandbox Paypal Business',
            'url' => 'https://sandbox.paypal.com/',
            'api_data' => json_encode([
                'client_key' => 'AfGFZ63l-30heXk1Xf2iNiO0SnhhIKeaEq9uIsqQt4kPenxBk_ZNwFhLTDDRDsX1bdV8_uVTMPnBgLnK',
                'secret_key' => 'EECgn7P9B5dgKFFvQWFQ6AH0AGqmm1ibbl7G_7njz59SKX-EKvZWCeY9beP-a8TU64WoC6FwPqdreAak'
            ]),
            'vps_data' => json_encode([
                'ips' => '244.178.44.111',
                'username' => 'root',
                'password' => '@GDTCashier123!'
            ]),
            'type' => Paygate::TYPE_PAYPAL,
            'status' => Paygate::STATUS_ACTIVE,
            'limitation' => 1000,
            'mode' => Paygate::MODE_SANDBOX,
        ]);
    }
}
