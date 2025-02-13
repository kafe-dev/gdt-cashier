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
        $dispute_id = 'PP-R-GJB-10106359';
//        $params = [
//            "evidences" => [
//                [
//                    "evidence_type" => "PROOF_OF_FULFILLMENT",
//                    'notes' => 'Note Dispute',
//                    "evidence_info" => [
//                        [
//                            "carrier_name" => 'PARKNPARCEL',
//                            "tracking_number" => '123123222323',
//                        ]
//                    ],
//                ],
//            ],
//        ];


        $data = [
            'evidences' => [
                [
                    'evidence_type' => 'PROOF_OF_FULFILLMENT',
                    'evidence_info' => [
                        'tracking_info' => [
                            [
                                'carrier_name' => 'FEDEX',
                                'tracking_number' => '122533485',
                            ],
                        ],
                    ],
                    'notes' => 'Thông tin giao hàng được cung cấp.',
                ],
            ],
        ];


        $result = $paypalApi->provideEvidence($dispute_id, $data);
        echo '<pre>start-debug' . PHP_EOL;
        print_r($result) . PHP_EOL;
        die('--end--');

    }
}
