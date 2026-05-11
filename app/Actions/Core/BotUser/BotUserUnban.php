<?php

namespace App\Actions\Core\BotUser;

use App\Actions\Core\Telegram\TelegramQuery;

use App\Models\Core\BotUser;
use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\BotUserRecurrentSchedule;
use App\Models\Core\BotUserUnbanSchedule;
use App\Models\Core\Sending;
use App\Models\Core\TelegramChatMemberLog;
use App\Models\Core\TelegramChatMemberErrorLog;
use App\Models\Core\TelegramSendMessageSchedule;
use App\Models\Core\TelegramUnbanSchedule;
use App\Models\Core\TelegramUnbanScheduleErrorLog;
use App\Models\Core\TelegramUnbanScheduleLog;

use Telegram\Bot\Api;

class BotUserUnban
{
    public function handle($bot_user, $supergroups) {
        $telegramQuery = new TelegramQuery();
        $telegram = new Api($bot_user->bot->telegram_token);

        if (isset($supergroups[$bot_user->bot_id])) {
            foreach ($supergroups[$bot_user->bot_id] as $supergroup) {

                if ($supergroup->unban == 1 && isset($supergroup->telegram_id)) {
                    try {
                        return $telegramQuery->handle($bot_user->bot, 'unbanChatMember', ['chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id, 'only_if_banned' => true]);
                        $status = ($status['ok'] == true?1:0);

                        BotUserBanSchedule::where('bot_user_id', $bot_user->id)->where('run_status', 0)->update(['run_status' => 3]);
                        //BotUserRecurrentSchedule::where('bot_user_id', $bot_user->id)->where('run_status', 0)->update(['run_status' => 3]);

                        TelegramUnbanScheduleLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id, 'status' => $status]);

                        try {
                            $member = $telegramQuery->handle($bot_user->bot, 'getChatMember', ['chat_id' => $supergroup->telegram_id, 'user_id' => $bot_user->telegram_chat_id]);

                            TelegramChatMemberLog::create(['bot_user_id' => $bot_user->id, 'user_id' => $bot_user->telegram_chat_id, 'chat_id' => $supergroup->telegram_id, 'status' => $member->status, 'text' => $member['result']['status']]);

                            if ($member['result']['status'] != 'banned') {
                                BotUserUnbanSchedule::where('bot_user_id', $bot_user->id)->update(['run_status' => 1]);
                                BotUser::where('id', $bot_user->id)->update(['ban' => 0, 'unban' => 1, 'unban_time' => now()]);

                                $date = date('Y-m-d', time());
                                $datetime_start = $date." 00:00:00";
                                $datetime_end = $date." 23:59:59";

                                $sendings = Sending::select('sending_id')
                                    ->where('user_ban', 1)
                                    ->where('sending_datetime', '>=', $datetime_start)
                                    ->where('sending_datetime', '<=', $datetime_end)
                                    ->pluck('sending_id')
                                    ->toArray();

                                TelegramSendMessageSchedule::where('bot_user_id', $bot_user->id)->whereIn('sending_id', $sendings)->update(['run_status' => 3]);

                            } else {
                                BotUserUnbanSchedule::where('bot_user_id', $bot_user->id)->update(['run_status' => 0]);
                            }

                        } catch (\Exception $exception) {
                            TelegramChatMemberErrorLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' =>$bot_user->telegram_chat_id, 'text' => $exception]);
                        }

                    } catch (\Exception $exception) {
                        TelegramUnbanScheduleErrorLog::create(['bot_user_id' => $bot_user->id, 'chat_id' => $supergroup->telegram_id, 'user_id' =>$bot_user->telegram_chat_id, 'text' => $exception]);
                    }
                }

            }
        }

    }
}
