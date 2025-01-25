<?php

namespace App\Paygate;
use App\Models\Paygate;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
class PaygateBussiness
{
    public $paygate;
    public $provider;
    public function __construct(Paygate $paygate){

        /** @var Paygate $paygate */
//        if($paygate->type == 1){ //type is Paypal
//
//            $config = [
//                'mode'    => 'sandbox',
//                'sandbox' => [
//                    'client_id'         => $client_id,
//                    'client_secret'     => $client_secret,
//                    'app_id'            => 'PAYPAL_LIVE_APP_ID',
//                ],
//
//                'payment_action' => 'Sale',
//                'currency'       => 'USD',
//                'notify_url'     => 'https://your-site.com/paypal/notify',
//                'locale'         => 'en_US',
//                'validate_ssl'   => true,
//            ];
//            $this->paygate = new PayPalClient();
//        }
    }
}
