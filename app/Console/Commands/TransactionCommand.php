<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Helpers\TimeHelper;
use App\Models\Paygate;
use App\Models\Transaction;
use App\Paygate\PayPalAPI;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use function Pest\Laravel\json;

class TransactionCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle(): void {
        $this->fetchv1();
    }

    /**
     * Lấy các giao dịch tranh chấp từ trên Paypal về theo các tài khoản paypal được cấu hình sẵn.
     *
     * @return void
     * @throws \Throwable
     */
    public function fetch(): void {
        $paygates = Paygate::all();
        foreach ($paygates as $paygate) {
            echo $paygate->name . PHP_EOL;
            $api_data = $paygate->api_data ?? [];
            if (empty($api_data)) {
                return;
            }
            $config   = [
                'mode'           => 'sandbox',
                'sandbox'        => [
                    'client_id'     => $api_data['client_key'] ?? '',
                    'client_secret' => $api_data['secret_key'] ?? '',
                    'app_id'        => 'PAYPAL_LIVE_APP_ID',
                ],
                'payment_action' => 'Sale',
                'currency'       => 'USD',
                'notify_url'     => '#',
                'locale'         => 'en_US',
                'validate_ssl'   => true,
            ];
            $provider = new PayPalClient($config);
            $provider->getAccessToken();
            $now        = new DateTime();
            $start_date = $now->format('Y-m-d') . 'T00:00:00Z';
            $end_date   = $now->format('Y-m-d') . 'T23:59:59Z';
            //            $response = $provider->listInvoices([
            //                'start_date' => $start_date,
            //                'end_da   o;  te'   => $end_date,
            //            ]);
            $filters  = [
                'start_date' => '2025-01-01T00:00:00Z',
                'end_date'   => '2025-01-31T23:59:59Z',
            ];
            $response = $provider->listTransactions($filters);
            if (!empty($response['items'])) {
                foreach ($response['items'] as $item) {
                    $this->newDispute($item, $paygate->id);
                }
            }
        }
    }

    public function fetchv1() {
        $paygates = Paygate::all();
        foreach ($paygates as $paygate) {
            $paypalApi = new PayPalAPI($paygate); // true = sandbox mode
            $response  = $paypalApi->listTransaction('2025-01-01T00:00:00.000Z', '2025-01-31T00:00:00.000Z');
            if (!empty($response['transaction_details'])) {
                foreach ($response['transaction_details'] as $item) {
                    try {
                        $this->newTransaction($item, $paygate->id);
                    } catch (\Exception $exception) {
                        echo $exception->getMessage() . PHP_EOL;
                    }
                }
            }
        }
    }

    /**
     * Tạo mới bản ghi Dispute.
     *
     * @param $item
     *
     * @return void
     * @throws \JsonException
     * @throws \DateMalformedStringException
     */
    public function newTransaction($item, $paygate_id): void {
        /** @var Transaction $transaction */
        $transaction                              = new Transaction();
        $transaction->transaction_id              = $item['transaction_info']['transaction_id'];
        $transaction->transaction_event_code      = $item['transaction_info']['transaction_event_code'];
        $transaction->transaction_initiation_date = TimeHelper::convertDateTime($item['transaction_info']['transaction_initiation_date']);
        $transaction->transaction_updated_date    = TimeHelper::convertDateTime($item['transaction_info']['transaction_updated_date']);
        $transaction->transaction_amount_currency = $item['transaction_info']['transaction_amount']['currency'] ?? '';
        $transaction->transaction_amount_value    = $item['transaction_info']['transaction_amount']['value'] ?? 0;
        $transaction->transaction_status          = $item['transaction_info']['transaction_status'] ?? '';
        $transaction->transaction_subject         = $item['transaction_info']['transaction_subject'] ?? '';
        $transaction->ending_balance_currency     = $item['transaction_info']['ending_balance']['currency_code'] ?? '';
        $transaction->ending_balance_value        = $item['transaction_info']['ending_balance']['currency_value'] ?? 0;
        $transaction->available_balance_currency  = $item['transaction_info']['available_balance']['currency_code'];
        $transaction->available_balance_value     = $item['transaction_info']['available_balance']['value'] ?? 0;
        $transaction->protection_eligibility      = $item['transaction_info']['protection_eligibility'];
        $transaction->payer_info                  = json_encode($item['payer_info'], JSON_THROW_ON_ERROR);
        $transaction->shipping_info               = json_encode($item['shipping_info'], JSON_THROW_ON_ERROR);
        $transaction->cart_info                   = json_encode($item['cart_info'], JSON_THROW_ON_ERROR);
        $transaction->store_info                  = json_encode($item['store_info'], JSON_THROW_ON_ERROR);
        $transaction->incentive_info              = json_encode($item['incentive_info'], JSON_THROW_ON_ERROR);
        if (!$transaction->save()) {
            Logs::create($transaction->errors());
        }
        echo $transaction->transaction_id . PHP_EOL;
    }
}
