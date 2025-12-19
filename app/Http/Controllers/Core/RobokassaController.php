<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\PaySystemCallback;

class RobokassaController
{
    public function callback() {
        $source = file_get_contents('php://input');
        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => $source]);

        $source = json_encode($source);
        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => $source]);

        $source = json_encode($source);
        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => json_decode(json_encode($_POST))]);
    }
}
