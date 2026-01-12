<?php
namespace App\Http\Controllers\Core;

use App\Models\Core\Pay;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;

class RobokassaController
{
    public function callback() {
        $payMakeSuccessful = new PayMakeSuccessful();
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $source = file_get_contents('php://input');

        $result_k = [];
        $result_v = [];

        $A = explode('&', $source);
        foreach ($A as $value) {
            $AA = explode('=', $value);
            foreach ($AA as $k => $v) {
                if ($k == 0) $result_k[] = $v;
                if ($k == 1) $result_v[] = $v;
            }
        }

        $result = array_combine($result_k, $result_v);
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        $paySystemCallbackCreate->handle($result, 'robokassa');

        $requestBody = json_decode($result, true);

        $check_pay = Pay::where('id', $requestBody['inv_id'])->where('status', 1)->first();

        if (!$check_pay) {
            $payMakeSuccessful->handle($result, $requestBody['inv_id'], NULL, NULL, $requestBody['Fee']);
        }

    }
}
