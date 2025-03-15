<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
use DateTime;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
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
        $capture_id = '6X822981VX905503D'; //transaction_id cần hoàn
        $result =  $paypalApi->issueRefund($capture_id);
        echo '<pre>';
        print_r($result);
        die;
    }


    public function test()
    {
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
                    'notes' => '',
                    'documents' => [],
                ],
            ],
        ];

        echo json_encode($data);
        die;
    }
}
