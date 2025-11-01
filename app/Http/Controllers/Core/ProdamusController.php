<?php
namespace App\Http\Controllers\Core;

use App\Actions\Core\PaySystemCallback\PaySystemCallbackCreate;

class ProdamusController
{
    public function callback() {
        $paySystemCallbackCreate = new PaySystemCallbackCreate();
        $source = file_get_contents('php://input');
        $paySystemCallbackCreate->handle($source, 'prodamus');

        http_response_code(200);
        return 'success';
    }
}
