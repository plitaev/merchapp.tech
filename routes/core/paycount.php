<?php
use App\Http\Controllers\Core\PayCountController;
use Illuminate\Support\Facades\Route;

Route::controller(PayCountController::class)->group(function() {
    Route::get('/paycount/load', 'load');
    Route::post('/paycount/load_post', 'load_post');
});
