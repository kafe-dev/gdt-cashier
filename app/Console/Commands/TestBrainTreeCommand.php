<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class TestBrainTreeCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-brain-tree:run';

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


    }

    public function braintree()
    {
        $gateway = new \Braintree\Gateway([
            'environment' => 'sandbox',
            'merchantId' => '89thqwdrhj5br9sk',
            'publicKey' => 'wk3f9fp43g9g4sm9',
            'privateKey' => 'bf22e85da8716950de47492ddab07566'
        ]);

        try {
            // Sử dụng search() để truy vấn dispute theo ngày
            $collection = $gateway->dispute()->search([
                \Braintree\DisputeSearch::receivedDate()->between('2024-01-01', '2025-2-28')
            ]);


            foreach ($collection as $dispute) {
                print_r($dispute);
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function api()
    {
        $paygate    = Paygate::find(3);
        $paypalApi  = new PayPalAPI($paygate);
        $dispute_id = 'PP-R-WAS-10106358';

    }


}
