<?php

namespace App\Http\Controllers\Core;

class PayController
{
    public function create(string $pay_system_alias, int $product_id) {
        return $pay_system_alias." - ".$product_id;
    }
}
