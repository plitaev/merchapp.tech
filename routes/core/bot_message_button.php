<?php

use App\Http\Controllers\Core\BotMessageButtonController;
use Illuminate\Support\Facades\Route;

Route::controller(BotMessageButtonController::class)->group(function() {
    Route::get('/go/{bot_message_button_id}/{chat_id}', 'go');
});
