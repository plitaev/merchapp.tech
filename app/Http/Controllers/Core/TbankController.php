<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\Pay;

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
                $check_pay = Pay::where('id', $requestBody['OrderId'])->where('status', 1)->first();

                if ($check_pay) {
                    $payment_method_id = (isset($requestBody['RebillId'])?$requestBody['RebillId']:NULL);
                    $payMakeSuccessful->handle($source, $requestBody['OrderId'], $requestBody['Token'], $payment_method_id, NULL);
                }

            }

        }
    }
}
