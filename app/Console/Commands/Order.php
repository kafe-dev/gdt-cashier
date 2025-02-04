<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
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
        $this->fetchv1();
    }

    /**
     * @throws \Throwable
     * @throws \JsonException
     */
    public function fetch()
    {
        $paygates = Paygate::all();
        /** @var Paygate $paygate */
        foreach ($paygates as $paygate) {
            echo $paygate->name.PHP_EOL;
            $api_data = $paygate->api_data;
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
//            $response = $provider->listInvoices([
//                'start_date' => '2024-12-01T00:00:00Z',
//                'end_date' => '2025-01-31T23:59:5 9Z',
//            ]);

            $response = $provider->listInvoices();
            echo '<pre>';
            print_r($response);
            die;

            if (! empty($response['items'])) {
                foreach ($response['items'] as $item) {
                    $created = $this->newOrder($item,$paygate->id);
                    if ($created) {
                        echo 'Thêm thành công công '.$created->id.PHP_EOL;
                    }
                }
            }
        }

    }

    /**
     * @throws \JsonException
     */
    protected function newOrder($item,$paygate_id)
    {
        $order = new \App\Models\Order;
        $order->paygate_id = $paygate_id;
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

    public function fetchv1() {
        $paygates = Paygate::all();
        foreach ($paygates as $paygate) {
            $api_data = $paygate->api_data??[];
            $paypalApi = new PayPalAPI($api_data['client_key'],$api_data['secret_key'],true); // true = sandbox mode
            $orders = $paypalApi->listOrder();
            echo '<pre>';
            print_r($orders);
            die;

        }
    }
}
