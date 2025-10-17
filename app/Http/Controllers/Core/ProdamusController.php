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
            $binding_id = (isset($Adata['binding_id'])?$Adata['binding_id']:NULL);
            $payMakeSuccessful->handle($source, $Adata['order_num'], $Adata['order_id'], $binding_id, $Adata['commission_sum']);
        }

        http_response_code(200);
        return 'success';
    }
}
