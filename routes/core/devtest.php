<?php

use App\Http\Controllers\Core\DevTestController;
use Illuminate\Support\Facades\Route;

Route::controller(DevTestController::class)->group(function() {
    Route::get('/devtest', 'devtest');
});
