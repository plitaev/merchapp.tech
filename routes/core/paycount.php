<?php
use App\Http\Controllers\Core\PayCountController;
use Illuminate\Support\Facades\Route;

Route::controller(PayCountController::class)->group(function() {
    Route::get('/paycount/load', 'load');
    Route::post('/paycount/load_post', 'load_post');

    Route::get('/paycount/change_number', 'change_number');
    Route::post('/paycount/change_number_post', 'change_number_post');

    Route::get('/paycount/list', 'list');
    Route::get('/paycount/callback/{email}/{phone}/{price}', 'callback');

    Route::get('/paycount/callback_run', 'callback_run');
});
