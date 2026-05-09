<?php

namespace App\Actions\Core\Auto;

use Telegram\Bot\Api;

use App\Models\Core\BotUser;
use App\Models\Core\TelegramBusinessOpening;

class TelegramBusinessOpeningHours
{
    public function handle() {
        $bot_users = BotUser::with('bot')->select('id', 'telegram_chat_id', 'bot_id')->where('business_bot_account', 1)->get();

        foreach ($bot_users as $bot_user) {

            $res = $telegram->getChat(['chat_id' => $bot_user->telegram_chat_id]);

            $telegram_data_hash = md5(json_encode($res));

            if (isset($res['business_opening_hours']['time_zone_name'])) {
                BotUser::where('id', $bot_user->id)->update(['time_zone_name' => $res['business_opening_hours']['time_zone_name']]);
            }

            $res = $res['business_opening_hours']['opening_hours'];

            $check = TelegramBusinessOpening::where('bot_user_id', $bot_user->id)->where('telegram_data_hash', $telegram_data_hash)->count();

            if ($check == 0) {
                TelegramBusinessOpening::where('bot_user_id', $bot_user->id)->delete();

                foreach ($res as $data) {
                    TelegramBusinessOpening::create([
                        'bot_user_id' => $bot_user->id,
                        'opening_minute' => $data['opening_minute'],
                        'closing_minute' => $data['closing_minute'],
                        'telegram_data_hash' => $telegram_data_hash
                    ]);
                }
            }
        }

    }
}
