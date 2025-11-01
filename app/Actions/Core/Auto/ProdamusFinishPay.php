<?php
namespace App\Actions\Core\Auto;

use App\Actions\Core\Pay\PayMakeSuccessful;
use App\Models\Core\PaySystemCallback;

class ProdamusFinishPay {
    public function handle() {
        $payMakeSuccessful = new PayMakeSuccessful();

        $res = PaySystemCallback::where('pay_system_id', 2)->where('run_status', 0)->orderBy('created_at')->take(1)->get();
        foreach ($res as $data) {
            PaySystemCallback::where('id', $data->id)->update(['run_status' => 1]);

            $Adata=json_decode($data->callback,true);

            if ($Adata['payment_status']=='success') {
                $binding_id = (isset($Adata['binding_id'])?$Adata['binding_id']:NULL);

                if ($Adata['order_num'] == '') return 'success';

                $payMakeSuccessful->handle($data->callback, $Adata['order_num'], $Adata['order_id'], $binding_id, $Adata['commission_sum']);
            }

        }

    }
}
