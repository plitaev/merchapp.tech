<?php

use App\Http\Controllers\Core\ConverterController;
use Illuminate\Support\Facades\Route;

Route::controller(ConverterController::class)->group(function() {
    Route::get('/converter/clean', 'clean');
    Route::get('/converter/load_users', 'load_users');
});
