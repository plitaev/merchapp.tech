<?php

use App\Http\Controllers\Core\BotController;
use Illuminate\Support\Facades\Route;

Route::controller(BotController::class)->group(function() {
    Route::get('/bot/{bot_id}/create', 'create');
});
