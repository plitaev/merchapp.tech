<?php

namespace App\Actions\Core\Pay;

use Filament\Notifications\Notification;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserBanByDeletePay;
use App\Actions\Core\DateEnd\DateEnd;
use App\Actions\Project\ClubAccess\BotCabinetRecurrentCheck;

use App\Models\Core\BotAdminLog;
use App\Models\Core\BotUser;
use App\Models\Core\Pay;

class PayRefund {
    public function handle(int $id) {

        $botSendMessage = new BotSendMessage();

        $pay = Pay::find($id);
        Pay::where('id', $id)->update(['status' => 2]);

        $bot_user = BotUser::with('bot')->find($pay->bot_user_id);

        $dateEnd = new DateEnd();
        $dateEnd->handle($pay->bot_user, 'Y-m-d');

        $botUserBanByDeletePay = new BotUserBanByDeletePay();
        $a = $botUserBanByDeletePay->handle($bot_user);

        $botCabinetRecurrentCheck = new BotCabinetRecurrentCheck();
        $botCabinetRecurrentCheck->handle($bot_user);

        BotAdminLog::create(['bot_user_id' =>  $pay->bot_user_id, 'user_id' => auth()->id(), 'name' =>'Возврат платежа '.$id]);

        Notification::make()
            ->title($a)
            ->success()
            ->send();

    }
}
