<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\ClubAccessMaxController;
use App\Http\Controllers\Project\App2MaxBotController;

Route::controller(ClubAccessMaxController::class)->group(function() {
    Route::post('/max/webhook/{bot_id}/club_access', 'club_access');
});

Route::controller(App2MaxBotController::class)->group(function() {
    Route::post('/max/webhook/app2maxbot', 'app2maxbot');
});
