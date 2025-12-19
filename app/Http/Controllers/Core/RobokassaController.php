<?php

namespace App\Http\Controllers\Core;

use App\Models\Core\PaySystemCallback;

class RobokassaController
{
    public function callback() {
        PaySystemCallback::create(['pay_system_id' => 3, 'callback' => '{"aaa": "robo script"}']);

        //$source = file_get_contents('php://input');
        //PaySystemCallback::create(['pay_system_id' => 3, 'callback' => $source]);
    }
}
