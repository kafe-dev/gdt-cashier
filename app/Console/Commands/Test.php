<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
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
     */
    public function handle()
    {
        $api_key = '246QXe6L2I2MmToEvX8KGHZ10mEaFhBDrr4CUhP3NLyfXpISc6sTw9Ii4ZP52AVr';
        $secret_key = 'WmmHqOrbZzOtPdvJWNlAKrbVPNvdWfyVyFNxthgP8Y6TG20yRsooagcDDVEgrZ5O';
        $order_id = '22704448150191538176';  // Thay bằng số lệnh giao dịch P2P

        $timestamp = time() * 1000; // Timestamp hiện tại
        $signature = hash_hmac('sha256', "order_id=$order_id&timestamp=$timestamp", $secret_key);

        $url = "https://api.binance.com/v2/private/order?order_id=$order_id&timestamp=$timestamp&signature=$signature";

// Gửi yêu cầu cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-MBX-APIKEY: ' . $api_key
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['code'])) {
            echo "Lỗi: " . $data['msg'];
        } else {
            echo '<pre>';
            print_r($data);
            die;
        }
        die('end');
    }
}
