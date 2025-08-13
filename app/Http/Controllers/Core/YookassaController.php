<?php

namespace App\Http\Controllers\Core;
use Illuminate\Support\Facades\Log;

class YookassaController
{
    public function callback() {
        Log::info('yookassa', ['ok' => 'ok']);
        $source = file_get_contents('php://input');
        Log::info('kost', ['kost' => $source]);
    }
}
