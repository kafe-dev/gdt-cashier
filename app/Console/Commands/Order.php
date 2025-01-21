<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class Order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fetch();
    }

    public function fetch()
    {
        $client_id     = 'AZQx_MV1p-0hGUGYc9rw1oV-8fywNCd-0lwH9egkdWarYchpi7h0MaKpi6o7jsXSAduM08rJwWhBGYcN';
        $client_secret = 'EB8d-gT1R_XhMlbFJQiqmelYcCCLcv75u83TXP9k1Gzv3Kqs5R3_VqTn32IjaJ_wgPE_CgO4Ss3gY0WL';


        $config = [
            'mode'    => 'sandbox',
            'sandbox' => [
                'client_id'         => $client_id,
                'client_secret'     => $client_secret,
                'app_id'            => 'PAYPAL_LIVE_APP_ID',
            ],

            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => 'https://your-site.com/paypal/notify',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
        $provider      = new PayPalClient($config);
        //$provider->setApiCredentials($config);

        $accessToken = $provider->getAccessToken();
        $response = $provider->listInvoices([
            'start_date' => '2024-01-01T00:00:00Z',
            'end_date'   => '2024-01-31T23:59:5 9Z',
        ]);
        echo '<pre>';
        print_r($response);
        die;
    }
}
