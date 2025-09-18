<?php

use App\Http\Controllers\Core\ConverterController;
use Illuminate\Support\Facades\Route;

Route::controller(ConverterController::class)->group(function() {
    Route::get('/converter/clean', 'clean');
    Route::get('/converter/load_users', 'load_users');
    Route::get('/converter/load_getcourse_webhooks', 'load_getcourse_webhooks');
    Route::get('/converter/create_pays_from_webhook', 'create_pays_from_webhook');
    Route::get('/converter/cache_date_end', 'cache_date_end');
});
