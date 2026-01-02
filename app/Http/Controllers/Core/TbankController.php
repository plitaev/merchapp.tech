<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;

class TbankController
{
    public function callback() {
        $payMakeSuccessful = new PayMakeSuccessful();
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $source = file_get_contents('php://input');
        $paySystemCallbackCreate->handle($source, 'tbank');

        $requestBody = json_decode($source, true);

        if ($requestBody['Status']=='CONFIRMED') {

            if (isset($requestBody['OrderId']) && isset($requestBody['Token'])) {
                $payment_method_id = (isset($requestBody['RebillId'])?$requestBody['RebillId']:NULL);
                $payMakeSuccessful->handle($source, $requestBody['OrderId'], $requestBody['Token'], $payment_method_id, NULL);
            }

        }
    }
}
