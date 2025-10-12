<?php

use App\Http\Controllers\Core\TelegramController;
use Illuminate\Support\Facades\Route;

Route::controller(TelegramController::class)->group(function() {
    Route::post('/telegram/get_webhook_info/{token}/{webhook}', 'get_webhook_info');
    Route::post('/telegram/set_webhook/{id}/{token}/{webhook}', 'set_webhook');
    Route::post('/telegram/delete_webhook/{token}/{webhook}', 'delete_webhook');
    Route::get('/telegram/get_webhook_info/{token}/{webhook}', 'get_webhook_info');

});
