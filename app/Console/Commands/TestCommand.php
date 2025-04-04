<?php

namespace App\Console\Commands;

use App\Models\Carrier;
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
        $json = file_get_contents(__DIR__ . '/carrie.json');
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        foreach ($data as $code => $name) {
            $carrier = new Carrier();
            $carrier->code = $code;
            $carrier->name= $name;
            if($carrier->save()){
                echo $carrier->name . ' saved' . PHP_EOL;
            }else{
                var_dump($carrier->errors());
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
