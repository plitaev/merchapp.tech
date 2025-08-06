<?php
namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;

use App\Models\Core\BotUserBanSchedule;
use App\Models\Core\TelegramBanScheduleLogs;
use App\Models\Core\TelegramBanScheduleErrorLogs;
use App\Models\Core\TelegramRevokeInviteLinkLog;
use App\Models\Core\TelegramRevokeInviteLinkErrorLog;
use App\Models\Core\TelegramSupergroup;

class BotUserBanProcess
{
    public function handle() {

        $supergroups = [];
        $res = TelegramSupergroup::select('bot_id', 'telegram_id')->get();
        foreach ($res as $data) $supergroups[$data->bot_id][] = $data->telegram_id;

        $bans = BotUserBanSchedule::with('bot', 'bot_user')->where('run_status', 0)->get();

        foreach ($bans as $ban) {
            BotUserBanSchedule::where('id', $ban->id)->update(['run_status' => 1]);
            $telegram = new Api($ban->bot->telegram_token);

            if (isset($supergroups[$ban->bot->id])) {
                foreach ($supergroups[$ban->bot->id] as $supergroup) {

                    try {
                        $status = $telegram->banChatMember(['chat_id' => $supergroup, 'user_id' => $ban->bot_user->telegram_chat_id]);
                        TelegramBanScheduleLogs::create(['bot_user_id' => 1, 'chat_id' => $supergroup, 'user_id' =>$ban->bot->telegram_chat_id, 'text' => $status]);

                        if (isset($ban->bot_user->telegram_invite_link)) {

                            try {
                                $task = $telegram->revokeChatInviteLink(['chat_id' => $supergroup, 'invite_link' => $ban->bot_user->telegram_invite_link]);

                                TelegramRevokeInviteLinkLog::create(
                                    [
                                        'bot_user_id' => $ban->bot_user->id,
                                        'chat_id' => $supergroup,
                                        'invite_link' => $ban->bot_user->telegram_invite_link,
                                        'telegram_data' => json_encode($task, true)
                                    ]
                                );

                            } catch (\Exception $exception) {
                                TelegramRevokeInviteLinkErrorLog::create(
                                    [
                                        'bot_user_id' => $ban->bot_user->id,
                                        'chat_id' => $supergroup,
                                        'invite_link' => $ban->bot_user->telegram_invite_link,
                                        'text' => $exception
                                    ]
                                );
                            }

                        }

                    } catch (\Exception $exception) {
                        TelegramBanScheduleErrorLogs::create(['bot_user_id' => $ban->bot_user->id, 'chat_id' => $supergroup, 'user_id' =>$ban->bot_user->telegram_chat_id, 'text' => $exception]);
                    }

                }
            }
        }

    }
}
