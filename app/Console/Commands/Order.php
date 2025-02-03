<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use DateTime;
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

    /**
     * @throws \Throwable
     * @throws \JsonException
     */
    public function fetch()
    {
        $paygates = Paygate::all();
        foreach ($paygates as $paygate) {
            echo $paygate->name.PHP_EOL;
            $api_data = json_decode($paygate->api_data, true, 512, JSON_THROW_ON_ERROR) ?? [];
            $config = [
                'mode' => 'sandbox',
                'sandbox' => [
                    'client_id' => $api_data['client_key'] ?? '',
                    'client_secret' => $api_data['secret_key'] ?? '',
                    'app_id' => 'PAYPAL_LIVE_APP_ID',
                ],
                'payment_action' => 'Sale',
                'currency' => 'USD',
                'notify_url' => '#',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ];
            $provider = new PayPalClient($config);
            $provider->getAccessToken();
            $now = new DateTime;
            $start_date = $now->format('Y-m-d').'T00:00:00Z';
            $end_date = $now->format('Y-m-d').'T23:59:59Z';
            //            $response = $provider->listInvoices([
            //                'start_date' => $start_date,
            //                'end_da   o;  te'   => $end_date,
            //            ]);
            $response = $provider->listInvoices([
                'start_date' => '2024-01-01T00:00:00Z',
                'end_date' => '2024-01-31T23:59:5 9Z',
            ]);
            if (! empty($response['items'])) {
                foreach ($response['items'] as $item) {
                    //
                }
            }
        }
        $client_id = 'AfGFZ63l-30heXk1Xf2iNiO0SnhhIKeaEq9uIsqQt4kPenxBk_ZNwFhLTDDRDsX1bdV8_uVTMPnBgLnK';
        $client_secret = 'EECgn7P9B5dgKFFvQWFQ6AH0AGqmm1ibbl7G_7njz59SKX-EKvZWCeY9beP-a8TU64WoC6FwPqdreAak';
        $config = [
            'mode' => 'sandbox',
            'sandbox' => [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'app_id' => 'PAYPAL_LIVE_APP_ID',
            ],
            'payment_action' => 'Sale',
            'currency' => 'USD',
            'notify_url' => 'https://your-site.com/paypal/notify',
            'locale' => 'en_US',
            'validate_ssl' => true,
        ];
        $provider = new PayPalClient($config);
        $provider->getAccessToken();
        $now = new DateTime;
        $start_date = $now->format('Y-m-d').'T00:00:00Z';
        $end_date = $now->format('Y-m-d').'T23:59:59Z';
        //        $response      = $provider->listInvoices([
        //            'start_date' => $start_date,
        //            'end_date'   => $end_date,
        //        ]);
        $response = $provider->listInvoices([
            'start_date' => '2024-01-01T00:00:00Z',
            'end_date' => '2024-01-31T23:59:5 9Z',
        ]);
        if (! empty($response['items'])) {
            foreach ($response['items'] as $item) {
                $created = $this->newOrder($item);
                if ($created) {
                    echo 'Thêm thành công công '.$created->id.PHP_EOL;
                }
            }
        }
    }

    /**
     * @throws \JsonException
     */
    protected function newOrder($item)
    {
        $order = new \App\Models\Order;
        $order->code = $item['id'] ?? '';
        $order->status = $item['status'] ?? '';
        $order->invoicer_email_address = $item['invoicer']['email_address'] ?? '';
        $order->billing_info = $item['primary_recipients'][0]['billing_info'] ? json_encode($item['primary_recipients'][0]['billing_info'], JSON_THROW_ON_ERROR) : '';
        $order->amount = $item['amount']['value'] ?? '';
        $order->currency_code = $item['amount']['currency_code'] ?? '';
        $order->paid_amount = $item['payments']['paid_amount']['value'] ?? '';
        $order->paid_currency_code = $item['payments']['paid_amount']['currency_code'] ?? '';
        $order->link = $item['links'][0]['href'] ?? '';
        $order->created_at = $item['detail']['metadata']['create_time'] ?? '';
        $order->updated_at = $item['detail']['metadata']['create_time'] ?? '';
        if (! $order->save()) {
            Logs::create($order->errors());

            return false;
        }

        return $order;
    }
}
