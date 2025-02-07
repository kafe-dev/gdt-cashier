<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class TestCommand extends Command
{
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
     * @throws \Exception
     */
    public function handle()
    {
        $paygate = Paygate::find(3);
        $paypalApi = new PayPalAPI($paygate);
        $dispute_id = 'PP-R-AWA-10106528';
        $result = $paypalApi->sendDisputeMessage($dispute_id,'tesst api nha');
        echo '<pre>';
        print_r($result);
        die;

    }

}
