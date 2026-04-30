<?php

use App\Http\Controllers\Core\DevTestController;
use Illuminate\Support\Facades\Route;

Route::controller(DevTestController::class)->group(function() {
    Route::get('/devtest', 'devtest');
    Route::get('/change_web_password/{email}', 'change_web_password');
});
