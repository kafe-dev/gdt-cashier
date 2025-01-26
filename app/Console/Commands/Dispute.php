<?php

namespace App\Console\Commands;

use App\Models\Paygate;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Dispute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispute:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->fetch();
    }

    /**
     * Lấy các giao dịch tranh chấp từ trên Paypal về theo các tài khoản paypal được cấu hình sẵn.
     *
     * @throws \Throwable
     */
    public function fetch(): void
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
            $response = $provider->listDisputes([
                'start_date' => '2024-01-01T00:00:00Z',
                'end_date' => '2024-01-31T23:59:5 9Z',
            ]);
            if (! empty($response['items'])) {
                foreach ($response['items'] as $item) {
                    $this->newDispute($item);
                }
            }
        }
    }

    /**
     * Tạo mới bản ghi Dispute.
     *
     *
     * @throws \JsonException
     */
    public function newDispute($item): void
    {
        $dispute = new \App\Models\Dispute;
        $dispute->dispute_id = $item['dispute_id'];
        $dispute->created_at = $item['create_time'];
        $dispute->buyer_transaction_id = $item['disputed_transactions'][0]['buyer_transaction_id'] ?? '';
        $dispute->merchant_id = $item['disputed_transactions'][0]['seller']['merchant_id'] ?? '';
        $dispute->reason = $item['reason'];
        $dispute->status = $item['status'];
        $dispute->dispute_state = $item['dispute_state'];
        $dispute->dispute_amount_currency = $item['dispute_amount']['currency_code'];
        $dispute->dispute_amount_value = $item['dispute_amount']['value'];
        $dispute->dispute_life_cycle_stage = $item['dispute_life_cycle_stage'];
        $dispute->dispute_channel = $item['dispute_channel'];
        $dispute->seller_response_due_date = $item['seller_response_due_date'];
        $dispute->link = $item['links'][0]['href'];
        if (! $dispute->save()) {
            echo json_encode($dispute->errors(), JSON_THROW_ON_ERROR).PHP_EOL;
        }
        echo $dispute->dispute_id.PHP_EOL;
    }
}
