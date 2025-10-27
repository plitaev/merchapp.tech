<?php

use App\Http\Controllers\Core\GoogleController;
use Illuminate\Support\Facades\Route;

Route::controller(GoogleController::class)->group(function() {
    Route::get('/google/send_banneds', 'send_banneds');
    Route::get('/google/send_recurrent_fail', 'send_recurrent_fail');
    Route::get('/google/send_non_active', 'send_non_active');
    Route::get('/google/send_recurrent_plan', 'send_recurrent_plan');
    Route::get('/google/send_recurrent_fail_prodamus', 'send_recurrent_fail_prodamus');
});
