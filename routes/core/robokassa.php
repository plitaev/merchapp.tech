<?php
use App\Http\Controllers\Core\RobokassaController;
use Illuminate\Support\Facades\Route;

Route::controller(RobokassaController::class)->group(function() {
    Route::post('/robokassa/callback', 'callback');
    Route::post('/robokassa/callback_fail', 'callback_fail');
    Route::get('/robokassa/recurrent', 'robokassa_recurrent');
});
