<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;

class ProdamusController
{
    public function callback() {
        $payMakeSuccessful = new PayMakeSuccessful();
        $paySystemCallbackCreate = new PaySystemCallbackCreate();

        $source = file_get_contents('php://input');
        $Adata=json_decode($source,true);

        $paySystemCallbackCreate->handle($source, 'prodamus');

        if ($Adata['payment_status']=='success') {
            $payMakeSuccessful->handle($source, $Adata['order_num'], 111, 222, $Adata['comission']);
        }

        http_response_code(200);
        return 'success';
    }
}
