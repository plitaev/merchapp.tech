<?php
namespace App\Http\Controllers\Core;

use Illuminate\Support\Facades\DB;

use App\Actions\Core\Yookassa\YookassaMakePaySuccessful;

class YookassaController
{
    public function callback() {
        $yookassaMakePaySuccessful = new YookassaMakePaySuccessful();

        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);

        if ($requestBody['event']=='payment.succeeded' || $requestBody['status']=='succeeded') {

            if (isset($requestBody['object']['metadata']['order_number']) || isset($requestBody['metadata']['order_number'])) {
                $yookassaMakePaySuccessful->handle($requestBody, $source);
            }

        }
    }
}
