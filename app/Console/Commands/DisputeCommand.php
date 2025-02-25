<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Helpers\TimeHelper;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class DisputeCommand extends Command {

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
     * @throws \Throwable
     */
    public function handle(): void {
        $this->fetchv1();
    }

    /**
     * Tạo mới bản ghi Dispute.
     *
     * @param $item
     *
     * @return void
     * @throws \JsonException
     */
    public function newDispute($item, $paygate_id): void {
        $dispute                           = new \App\Models\Dispute();
        $dispute->paygate_id               = $paygate_id;
        $dispute->dispute_id               = $item['dispute_id'];
        $dispute->created_at               = $item['create_time'];
        $dispute->buyer_transaction_id     = $item['disputed_transactions'][0]['buyer_transaction_id'] ?? '';
        $dispute->merchant_id              = $item['disputed_transactions'][0]['seller']['merchant_id'] ?? '';
        $dispute->reason                   = $item['reason'];
        $dispute->status                   = $item['status'];
        $dispute->dispute_state            = $item['dispute_state'];
        $dispute->dispute_amount_currency  = $item['dispute_amount']['currency_code'];
        $dispute->dispute_amount_value     = $item['dispute_amount']['value'];
        $dispute->dispute_life_cycle_stage = $item['dispute_life_cycle_stage'];
        $dispute->dispute_channel          = $item['dispute_channel'];
        $dispute->seller_response_due_date = $item['seller_response_due_date'];
        $dispute->link                     = $item['links'][0]['href'];
        if (!$dispute->save()) {
            echo json_encode($dispute->errors(), JSON_THROW_ON_ERROR) . PHP_EOL;
        }
        echo $dispute->dispute_id . PHP_EOL;
    }

    /**
     * $  * @throws \JsonException
     * @throws \Exception
     */
    public function fetchv1() {
        $paygates = Paygate::all();
        foreach ($paygates as $paygate) {
            $paypalApi = new PayPalAPI($paygate);
            $response  = $paypalApi->listDispute('2025-01-01T00:00:00.000Z');
            if (!empty($response['items'])) {
                foreach ($response['items'] as $item) {
                    try {
                        $this->newDispute($item, $paygate->id);
                        echo $item['dispute_id'] . '=>CREATED' . PHP_EOL;
                    } catch (\Exception $exception) {
                        //Dispute đã tồn tại trên HT. Sẽ cập nhập trạng thái Dispute.
                        $this->updateStatus($item['dispute_id'], $item['status']);
                        echo $item['dispute_id'] . '=>' . $item['status'] . PHP_EOL;
                    }
                }
            } else {
                echo 'Today is not dispute' . PHP_EOL;
            }
        }
    }

    /**
     * Update status of dispute
     *
     * @param string $dispute_id
     * @param string $status
     *
     * @return void
     * @throws \Exception
     */
    public function updateStatus(string $dispute_id, string $status): void {
        $dispute = \App\Models\Dispute::where('dispute_id', $dispute_id)->first();
        if (!$dispute) {
            return;
        }
        $dispute->status = $status;
        if (!$dispute->save()) {
            return;
        }
    }
}
