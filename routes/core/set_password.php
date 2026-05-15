<?php

use App\Http\Controllers\SetPasswordController;
use Illuminate\Support\Facades\Route;

Route::controller(SetPasswordController::class)->group(function() {
    Route::get('/set_password/{id}', 'set_password');
});
