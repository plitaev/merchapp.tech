<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Actions\Core\Auto\BotSetFunnels;
use App\Actions\Core\Auto\BotUserBanProcess;
use App\Actions\Core\Auto\BotUserRecurrentSchedulerProcess;
use App\Actions\Core\Auto\BotUserSetRecurrentScheduler;
use App\Actions\Core\Auto\BotUserSetBanScheduler;
use App\Actions\Core\Auto\BotUserUnbanProcess;

use App\Actions\Core\Auto\TelegramBusinessOpeningHours;
use App\Actions\Core\Auto\TelegramBusinessResponceInOpeningHours;
use App\Actions\Core\Auto\TelegramSendMessageScheduleProcess;
use App\Actions\Core\Auto\TelegramScheduleDeleteMessageFill;
use App\Actions\Core\Auto\TelegramScheduleDeleteMessageProcess;
use App\Actions\Core\Auto\TelegramScheduleEditMessageFill;
use App\Actions\Core\Auto\TelegramScheduleEditMessageProcess;

class AutoController extends Controller
{

    public function bot_set_funnels() {
        $botSetFunnels = new BotSetFunnels();
        return $botSetFunnels->handle();
    }

    public function bot_user_set_recurrent_scheduler() {
        $botUserSetRecurrentScheduler = new BotUserSetRecurrentScheduler();
        return $botUserSetRecurrentScheduler->handle();
    }

    public function bot_user_recurrent_scheduler_process() {
        $botUserRecurrentSchedulerProcess = new BotUserRecurrentSchedulerProcess();
        return $botUserRecurrentSchedulerProcess->handle();
    }

    public function bot_user_set_ban_scheduler() {
        $botUserSetBanScheduler = new BotUserSetBanScheduler();
        return $botUserSetBanScheduler->handle();
    }

    public function bot_user_ban_process()
    {
        $botUserBanProcess = new BotUserBanProcess();
        return $botUserBanProcess->handle();
    }

    public function bot_user_unban_process()
    {
        $botUserUnbanProcess = new BotUserUnbanProcess();
        return $botUserUnbanProcess->handle();
    }

    public function telegram_business_opening_hours()
    {
        $telegramBusinessOpeningHours = new TelegramBusinessOpeningHours();
        return $telegramBusinessOpeningHours->handle();
    }

    public function telegram_business_responce_in_opening_hours()
    {
        $telegramBusinessResponceInOpeningHours = new TelegramBusinessResponceInOpeningHours();
        return $telegramBusinessResponceInOpeningHours->handle();
    }

    public function telegram_send_message_schedule() {
        $telegramSendMessageScheduleProcess = new TelegramSendMessageScheduleProcess();
        return $telegramSendMessageScheduleProcess->handle();
    }

    public function telegram_schedule_edit_messages() {
        $telegramScheduleEditMessageFill = new TelegramScheduleEditMessageFill();
        return $telegramScheduleEditMessageFill->handle();
    }

    public function telegram_schedule_edit_messages_process() {
        $telegramScheduleEditMessageProcess = new TelegramScheduleEditMessageProcess();
        return $telegramScheduleEditMessageProcess->handle();
    }

    public function telegram_schedule_delete_messages() {
        $telegramScheduleDeleteMessageFill = new TelegramScheduleDeleteMessageFill();
        return $telegramScheduleDeleteMessageFill->handle();
    }

    public function telegram_schedule_delete_messages_process() {
        $telegramScheduleDeleteMessageProcess = new TelegramScheduleDeleteMessageProcess();
        return $telegramScheduleDeleteMessageProcess->handle();
    }
}
