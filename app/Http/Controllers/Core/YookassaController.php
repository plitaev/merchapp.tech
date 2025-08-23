<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;

class YookassaController
{
    public function callback() {
        $payMakeSuccessful = new PayMakeSuccessful();
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $source = file_get_contents('php://input');
        $paySystemCallbackCreate->handle($source, 'yookassa');

        $requestBody = json_decode($source, true);

        if ($requestBody['event']=='payment.succeeded' || $requestBody['status']=='succeeded') {

            if (isset($requestBody['object']['metadata']['order_number']) && isset($requestBody['object']['id']) && isset($requestBody['object']['payment_method']['id'])) {

                if (isset($requestBody['object']['amount']['value']) && isset($requestBody['object']['income_amount']['value'])) {
                    $comission = $requestBody['object']['amount']['value']-$requestBody['object']['income_amount']['value'];
                } else {
                    $comission = NULL;
                }

                $payMakeSuccessful->handle($source, $requestBody['object']['metadata']['order_number'], $requestBody['object']['id'], $requestBody['object']['payment_method']['id'], $comission);
            }

        }
    }
}
