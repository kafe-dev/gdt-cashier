<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Test extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->test();
    }

    function fakeData() {


    }

    function test() {
        $client_id     = 'AYGeGYzfpK9LvuFz_oouH7OPzusBxDBME8ziVmqHlp3HWjf8PtNL94OsqYqV-JUjNy4UCfPiwcsoHnk3';
        $client_secret = 'EDbWwm7Gw2eMGTfntQCcsbs3hncjEgyN5hzboDA3KOvQLYgFc3REfMkZ2Buiwr6PzsFPZVJIEh_EzwMM';


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
        $response = $provider->listDisputes([
            'start_date' => '2024-01-01T00:00:00Z',
            'end_date'   => '2024-12-31T23:59:5 9Z',
        ]);
        echo '<pre>';
        print_r($response);
        die;
    }
}
