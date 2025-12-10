<?php
namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

use App\Actions\Core\Auto\BotSetFunnels;
use App\Actions\Core\Auto\BotUserBanProcess;
use App\Actions\Core\Auto\BotUserRecurrentSchedulerProcess;
use App\Actions\Core\Auto\BotUserSupergroupStatusDateEndEmpty;
use App\Actions\Core\Auto\BotUserSupergroupStatusDateEndExpired;
use App\Actions\Core\Auto\BotUserSetRecurrentScheduler;
use App\Actions\Core\Auto\BotUserSetBanScheduler;
use App\Actions\Core\Auto\BotUserUnbanProcess;
use App\Actions\Core\Auto\ProdamusFinishPay;
use App\Actions\Core\Auto\TelegramBusinessOpeningHours;
use App\Actions\Core\Auto\TelegramBusinessResponceInOpeningHours;
use App\Actions\Core\Auto\TelegramSendMessageScheduleProcess;
use App\Actions\Core\Auto\TelegramScheduleDeleteMessageFill;
use App\Actions\Core\Auto\TelegramScheduleDeleteMessageProcess;
use App\Actions\Core\Auto\TelegramScheduleEditMessageFill;
use App\Actions\Core\Auto\TelegramScheduleEditMessageProcess;
use App\Actions\Core\Auto\BotSetStatBotUserOnDay;
use App\Actions\Core\Auto\BotSendUserOnDay;
use App\Actions\Core\DateEnd\DateEndCalculateForAll;
use App\Models\Core\BotUser;


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

    public function prodamus_process() {
        $ProdamusFinishPay = new ProdamusFinishPay();
        return $ProdamusFinishPay->handle();
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

    public function stat_bot_user_on_day() {
        $botSetStatBotUserOnDay = new BotSetStatBotUserOnDay();
        return $botSetStatBotUserOnDay->handle();
    }

    public function send_bot_user_on_day() {
        $botSendUserOnDay = new BotSendUserOnDay();
        return $botSendUserOnDay->handle();
    }

    public function bot_user_supergroup_status_date_end_empty() {
        $botUserSupergroupStatusDateEndEmpty = new BotUserSupergroupStatusDateEndEmpty();
        return $botUserSupergroupStatusDateEndEmpty->handle();
    }

    public function bot_user_supergroup_status_date_end_expired(string $date_end) {
        $botUserSupergroupStatusDateEndExpired = new BotUserSupergroupStatusDateEndExpired();
        return $botUserSupergroupStatusDateEndExpired->handle($date_end);
    }

    public function bot_users_calculate_date_end() {
        $dateEndCalculateForAll = new DateEndCalculateForAll();
        return $dateEndCalculateForAll->handle();
    }

}
