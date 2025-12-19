<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\PaySystemCallback;

class RobokassaController
{
    public function callback() {
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

        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => $result]);

    }
}
