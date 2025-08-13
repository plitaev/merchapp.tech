<?php

namespace App\Http\Controllers\Core;
use Illuminate\Support\Facades\Log;

class YookassaController
{
    public function callback() {
        Log::info('yookassa', 'ok');
        $source = file_get_contents('php://input');
        Log::info('kost', $source);
    }
}
