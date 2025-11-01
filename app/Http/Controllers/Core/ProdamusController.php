<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;
use App\Models\Core\PaySystemCallback;

class ProdamusController
{
    public function callback() {
        $paySystemCallbackCreate = new PaySystemCallbackCreate();
        $source = file_get_contents('php://input');
        //$paySystemCallbackCreate->handle($source, 'prodamus');
        PaySystemCallback::create(['pay_system_id' => 2, 'callback' => $source]);

        //http_response_code(200);
        return 'success';
    }
}
