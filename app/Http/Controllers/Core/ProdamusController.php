<?php
namespace App\Http\Controllers\Core;

use App\Models\Core\PaySystemCallback;

class ProdamusController
{
    public function callback() {
        $source = file_get_contents('php://input');

        $source = str_replace('"1 \u043c\u0435\u0441\u044f\u0446"', '1 \u043c\u0435\u0441\u044f\u0446', $source);
        $source = str_replace('"6 \u043c\u0435\u0441\u044f\u0446\u0435\u0432"', '6 \u043c\u0435\u0441\u044f\u0446\u0435\u0432', $source);
        $source = str_replace('"12 \u043c\u0435\u0441\u044f\u0446\u0435\u0432"', '12 \u043c\u0435\u0441\u044f\u0446\u0435\u0432', $source);

        PaySystemCallback::create(['pay_system_id' => 2, 'callback' => $source]);
        return 'success';
    }
}
