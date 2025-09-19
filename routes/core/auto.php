<?php

use App\Http\Controllers\Core\AutoController;
use Illuminate\Support\Facades\Route;

Route::controller(AutoController::class)->group(function() {

    Route::get('/auto/bot_user_set_recurrent_scheduler', 'bot_user_set_recurrent_scheduler');
    Route::get('/auto/bot_user_set_ban_scheduler', 'bot_user_set_ban_scheduler');
    Route::get('/auto/bot_user_ban_process', 'bot_user_ban_process');
    Route::get('/auto/bot_user_unban_process', 'bot_user_unban_process');

    Route::get('/auto/telegram_business_opening_hours', 'telegram_business_opening_hours');
    Route::get('/auto/telegram_business_responce_in_opening_hours', 'telegram_business_responce_in_opening_hours');

    Route::get('/auto/telegram_send_message_schedule', 'telegram_send_message_schedule');

    Route::get('/auto/telegram_schedule_edit_messages', 'telegram_schedule_edit_messages');
    Route::get('/auto/telegram_schedule_edit_messages_process', 'telegram_schedule_edit_messages_process');

    Route::get('/auto/telegram_schedule_delete_messages', 'telegram_schedule_delete_messages');
    Route::get('/auto/telegram_schedule_delete_messages_process', 'telegram_schedule_delete_messages_process');
});
