<?php

use App\Http\Controllers\Core\GoogleController;
use Illuminate\Support\Facades\Route;

Route::controller(GoogleController::class)->group(function() {
    Route::get('/google/send_banneds', 'send_banneds');
    Route::get('/google/send_recurrent_fail', 'send_recurrent_fail');
});
