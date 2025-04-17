<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Helpers\TimeHelper;
use App\Http\Controllers\Dispute;
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
        $this->fetch();
    }

    /**
     * Create new dispute.
     *
     * @param array $item
     * @param int   $paygate_id
     *
     * @return \App\Models\Dispute|false
     */
    public function newDispute($item, $paygate_id) {
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
        $dispute->seller_response_due_date = !empty($item['seller_response_due_date']) ? $item['seller_response_due_date'] : now(); // Gán thời gian hiện tại
        $dispute->link                     = $item['links'][0]['href'];
        if ($dispute->save()) {
            return $dispute;
        }
        echo var_dump($dispute->errors()) . PHP_EOL;
        return false;
    }

    /**
     * Fetch dispute from PayPal.
     *
     * @return void
     * @throws \Exception
     */
    public function fetch(): void {
        foreach (Paygate::all() as $paygate) {
            $paypalApi = new PayPalAPI($paygate);
            $time_start = TimeHelper::getFirstDayOfCurrentMonth();
            $response  = $paypalApi->listDispute($time_start);
            if (empty($response['items'])) {
                echo 'Today is not dispute' . PHP_EOL;
                continue;
            }
            foreach ($response['items'] as $item) {
                $exits = \App\Models\Dispute::isUniqueDispute($item['dispute_id']);
                if ($exits) {
                    $status = $this->newDispute($item, $paygate->id) ? 'CREATED' : 'ERROR';
                } else {
                    $this->updateStatus($item['dispute_id'], $item['status']);
                    $status = $item['status'];
                }
                echo "{$item['dispute_id']} => {$status}" . PHP_EOL;
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
        \App\Models\Dispute::where('dispute_id', $dispute_id)->update(['status' => $status]);
    }
}
