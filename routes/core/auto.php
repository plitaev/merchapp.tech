<?php

use App\Http\Controllers\Core\AutoController;
use Illuminate\Support\Facades\Route;

Route::controller(AutoController::class)->group(function() {

    Route::get('/auto/bot_set_funnels', 'bot_set_funnels');

    Route::get('/auto/bot_user_set_recurrent_scheduler', 'bot_user_set_recurrent_scheduler');
    Route::get('/auto/bot_user_recurrent_scheduler_process', 'bot_user_recurrent_scheduler_process');

    Route::get('/auto/bot_user_supergroup_status_date_end_empty', 'bot_user_supergroup_status_date_end_empty');
    Route::get('/auto/bot_user_supergroup_status_date_end_expired/{date_end}', 'bot_user_supergroup_status_date_end_expired');
    Route::get('/auto/bot_users_calculate_date_end', 'bot_users_calculate_date_end');

    Route::get('/auto/bot_user_set_ban_scheduler', 'bot_user_set_ban_scheduler');
    Route::get('/auto/bot_user_ban_process', 'bot_user_ban_process');
    Route::get('/auto/bot_user_unban_process', 'bot_user_unban_process');

    Route::get('/auto/edgecenter_get_video_data', 'edgecenter_get_video_data');

    Route::get('/auto/telegram_business_opening_hours', 'telegram_business_opening_hours');
    Route::get('/auto/telegram_business_responce_in_opening_hours', 'telegram_business_responce_in_opening_hours');

    Route::get('/auto/prodamus_process', 'prodamus_process');
    Route::get('/auto/robokassa_recurrent_fail', 'robokassa_recurrent_fail');

    Route::get('/auto/telegram_send_message_schedule', 'telegram_send_message_schedule');

    Route::get('/auto/telegram_schedule_edit_messages', 'telegram_schedule_edit_messages');
    Route::get('/auto/telegram_schedule_edit_messages_process', 'telegram_schedule_edit_messages_process');

    Route::get('/auto/telegram_schedule_delete_messages', 'telegram_schedule_delete_messages');
    Route::get('/auto/telegram_schedule_delete_messages_process', 'telegram_schedule_delete_messages_process');

    Route::get('/auto/stat_bot_user_on_day', 'stat_bot_user_on_day');
    Route::get('/auto/send_bot_user_on_day', 'send_bot_user_on_day');


    Route::get('/auto/cron_test_1', 'cron_test_1');
    Route::get('/auto/cron_test_2', 'cron_test_2');

});
