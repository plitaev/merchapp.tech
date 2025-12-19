<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\PaySystemCallback;

class RobokassaController
{
    public function callback() {
        $source = file_get_contents('php://input');

        $result = [];

        $A = explode('&', $source);
        foreach ($A as $value) {
            $AA = explode('=', $value);
            foreach ($AA as $k => $v) {
                $result[$k] = $v;
            }
        }

        $result = json_encode($result);
        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => $source]);

        $result = json_decode($result);
        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => $source]);

    }
}
