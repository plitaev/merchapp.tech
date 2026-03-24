<?php

use App\Http\Controllers\Core\MaxController;
use Illuminate\Support\Facades\Route;

Route::controller(MaxController::class)->group(function() {
    Route::get('/max/send_file/{bot_id}/{bot_message_id}', 'send_file');
});
