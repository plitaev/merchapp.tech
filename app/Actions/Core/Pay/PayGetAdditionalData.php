<?php

namespace App\Actions\Core\Pay;

class PayGetAdditionalData
{
    public function handle(int $pay_system_id) {

        $additional_data = [];
        $additional_data['pay_system_id'] = $pay_system_id;

        return $additional_data;
    }
}
