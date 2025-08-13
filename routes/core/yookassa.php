<?php
use App\Http\Controllers\Core\YookassaController;
use Illuminate\Support\Facades\Route;

Route::controller(YookassaController::class)->group(function() {
    Route::post('/yookassa/callback', 'callback');
});
