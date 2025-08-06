<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\ClubAccessController;
use App\Http\Controllers\Project\App2BotController;

Route::controller(ClubAccessController::class)->group(function() {
    Route::post('/telegram/webhook/{bot_id}/club_access', 'club_access');
});

Route::controller(App2BotController::class)->group(function() {
    Route::post('/telegram/webhook/app2bot', 'app2bot');
});
