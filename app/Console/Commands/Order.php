<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Order extends Command {

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
    public function handle() {
        $this->fetch();
    }

    public function fetch() {
        $client_id     = 'AZQx_MV1p-0hGUGYc9rw1oV-8fywNCd-0lwH9egkdWarYchpi7h0MaKpi6o7jsXSAduM08rJwWhBGYcN';
        $client_secret = 'EB8d-gT1R_XhMlbFJQiqmelYcCCLcv75u83TXP9k1Gzv3Kqs5R3_VqTn32IjaJ_wgPE_CgO4Ss3gY0WL';
        $config        = [
            'mode'           => 'sandbox',
            'sandbox'        => [
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'app_id'        => 'PAYPAL_LIVE_APP_ID',
            ],
            'payment_action' => 'Sale',
            'currency'       => 'USD',
            'notify_url'     => 'https://your-site.com/paypal/notify',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
        $provider      = new PayPalClient($config);
        $provider->getAccessToken();
        $now        = new DateTime();
        $start_date = $now->format('Y-m-d') . 'T00:00:00Z';
        $end_date   = $now->format('Y-m-d') . 'T23:59:59Z';
        //        $response      = $provider->listInvoices([
        //            'start_date' => $start_date,
        //            'end_date'   => $end_date,
        //        ]);
        $response = $provider->listInvoices([
            'start_date' => '2024-01-01T00:00:00Z',
            'end_date'   => '2024-01-31T23:59:5 9Z',
        ]);
        if (!empty($response['items'])) {
            foreach ($response['items'] as $item) {
                /** @var \App\Models\Order $order */
                $order                         = new \App\Models\Order();
                $order->code                   = $item['id'] ?? '';
                $order->status                 = $item['status'] ?? '';
                $order->invoicer_email_address = $item['invoicer']['email_address'] ?? '';
                $order->billing_info           = $item['primary_recipients'][0]['billing_info'] ? json_encode($item['primary_recipients'][0]['billing_info']) : '';
                $order->amount                 = $item['amount']['value'] ?? '';
                $order->currency_code          = $item['amount']['currency_code'] ?? '';
                $order->paid_amount            = $item['payments']['paid_amount']['value'] ?? '';
                $order->paid_currency_code     = $item['payments']['paid_amount']['currency_code'] ?? '';
                $order->link                   = $item['links'][0]['href'] ?? '';
                $order->created_at             = $item['detail']['metadata']['create_time'] ?? '';
                $order->updated_at             = $item['detail']['metadata']['create_time'] ?? '';
                if (!$order->save()) {
                    Logs::create($order->getMessage());
                } else {
                    echo 'Thêm thanh cong - ' . $order->code . PHP_EOL;
                }
            }
        }
    }
}
