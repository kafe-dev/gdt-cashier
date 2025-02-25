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
        $accessToken = $paypalApi->getAccessToken();
        $disputeId = 'PP-R-YOW-10108337';
        $pdf = __DIR__ . '/1202_1.jpg';

        $input = [
            'evidences' => [
                [
                    'evidence_type' => 'OTHER',
                    'notes' => 'Test',
                ],
            ]
        ];

        $client = new Client([
            'base_uri' => 'https://api.sandbox.paypal.com',
            'timeout'  => 5.0
        ]);

        $multipart = new MultipartStream([
            [
                'name'     => 'input',
                'contents' => json_encode($input),
                'headers'  => ['Content-Type' => 'application/json']
            ],
            [
                'name'     => 'file1',
                'contents' => fopen($pdf, 'r'),
                'filename' => 'sample.jpg', // Chú ý: Đặt đúng tên file nếu là ảnh
                'headers'  => ['Content-Type' => 'image/jpeg'] // Đổi MIME type nếu là ảnh
            ],
        ]);

        $url = "/v1/customer/disputes/{$disputeId}/provide-evidence";

        $request = new Request('POST', $url, [
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type'  => "multipart/related; boundary={$multipart->getBoundary()}"
        ], $multipart);

        try {
            $response = $client->send($request);

            // Lấy mã phản hồi HTTP
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            echo "HTTP Status Code: " . $statusCode . PHP_EOL;
            echo "Response Body: " . $body . PHP_EOL;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Bắt lỗi nếu request thất bại
            echo "HTTP Request failed!" . PHP_EOL;
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                echo "HTTP Status Code: " . $response->getStatusCode() . PHP_EOL;
                echo "Error Response: " . $response->getBody()->getContents() . PHP_EOL;
            } else {
                echo "Error Message: " . $e->getMessage() . PHP_EOL;
            }
        }
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
