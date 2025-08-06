<?php

use App\Http\Controllers\Core\BotMessageController;
use Illuminate\Support\Facades\Route;

Route::controller(BotMessageController::class)->group(function() {
    Route::get('/bot/{bot_message_id}/send_to_admin', 'send_to_admin');
});
