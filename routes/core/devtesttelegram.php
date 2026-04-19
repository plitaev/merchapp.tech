<?php

use App\Http\Controllers\Core\DevTestTelegramController;
use Illuminate\Support\Facades\Route;

Route::controller(DevTestTelegramController::class)->group(function() {
    Route::get('/devtesttelegram', 'devtesttelegram');
});
