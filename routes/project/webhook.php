<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Project\ClubAccessController;

Route::controller(ClubAccessController::class)->group(function() {
    Route::post('/{messenger}/webhook/{bot_id}/club_access', 'club_access');
});
