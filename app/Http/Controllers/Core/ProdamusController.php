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
        $paySystemCallbackCreate->handle($source, 'prodamus');

        if ($_POST['payment_status']=='success') {
            $payMakeSuccessful->handle($source, $_POST['order_num'], 111, 222, $_POST['comission']);
        }

        http_response_code(200);
        return 'success';
    }
}
