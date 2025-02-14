<?php

namespace App\Console\Commands;

use App\Helpers\Logs;
use App\Models\Paygate;
use App\Paygate\PayPalAPI;
use Braintree\Gateway;
use DateTime;
use Illuminate\Console\Command;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class TestCommand extends Command {

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
    public function handle() {
        $this->apiUploadFileV1();
    }

    public function provide() {
        $paygate    = Paygate::find(3);
        $paypalApi  = new PayPalAPI($paygate);
        $dispute_id = 'PP-R-YFM-10106531';
        $data       = [
            'evidences' => [
                [
                    'evidence_type' => 'PROOF_OF_FULFILLMENT',
                    'evidence_info' => [
                        'tracking_info' => [
                            [
                                'carrier_name'    => 'FEDEX',
                                'tracking_number' => '122533485',
                            ],
                        ],
                    ],
                    'notes'         => 'Thông tin giao hàng được cung cấp.',
                    'documents'     => [
                        [
                            'name' => 'https://burgerprints.com/wp-content/uploads/2024/09/tracking-number-1.jpg',
                        ],
                    ],
                ],
            ],
        ];
        $result     = $paypalApi->provideEvidence($dispute_id, $data);
        echo '<pre>start-debug' . PHP_EOL;
        print_r($result) . PHP_EOL;
        die('--end--');
    }

    public function apiUploadFile() {
        $paygate       = Paygate::find(3);
        $paypalApi     = new PayPalAPI($paygate);
        $urlFile       = __DIR__ . '/1230-4-3.jpeg';
        $disputeId     = 'PP-R-EDS-10106530';
        $result_upload = $paypalApi->uploadFile($urlFile, $disputeId);
        echo '<pre>';
        print_r($result_upload);
        die;
    }

    public function apiUploadFileV1() {
        $paygate       = Paygate::find(3);
        $api_data = json_decode($paygate->api_data,true);
        $urlFile       = __DIR__ . '/1230-4-3.jpeg';
        $disputeId     = 'PP-R-EDS-10106530';
        $gateway = new Gateway([
            'environment' => 'sandbox', // Hoặc 'production' nếu chạy thật
            'merchantId' => '3QXE2HH2JPZF6',
            'publicKey' => $api_data['client_key'],
            'privateKey' => $api_data['secret_key']
        ]);
        $result = $gateway->documentUpload()->create([
            'kind' => BraintreeDocumentUpload::EVIDENCE_DOCUMENT,
            'file' => fopen('local_file.pdf', 'rb')
        ]);

        if ($result->success) {
            # document successfully uploaded
            $document = $result->documentUpload;
        } else {
            echo $result->errors;
        }
    }
}
