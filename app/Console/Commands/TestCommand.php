<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
use DateTime;
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
        $dispute_id = 'PP-R-WAS-10106358';

//        $data = [
//            'evidences' => [
//                [
//                    'evidence_type' => 'PROOF_OF_FULFILLMENT',
//                    'evidence_info' => [
//                        'tracking_info' => [
//                            [
//                                'carrier_name' => 'FEDEX',
//                                'tracking_number' => '122533485',
//                            ],
//                        ],
//                    ],
//                    'notes' => 'Thông tin giao hàng được cung cấp.',
//                    'documents'=>[],
//                ],
//            ],
//        ];

        $result = $paypalApi->test(
            __DIR__.'/1202_1.jpg',
            $dispute_id
        );

        echo '<pre>start-debug'.PHP_EOL;
        print_r($result).PHP_EOL;
        die('--end--');



        //$result = $paypalApi->provideEvidence($dispute_id, $data);
        echo '<pre>start-debug' . PHP_EOL;
        print_r($result) . PHP_EOL;
        die('--end--');

    }
}
