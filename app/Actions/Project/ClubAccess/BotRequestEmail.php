<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessage;
use App\Actions\Core\BotUser\BotUserSetListener;

class BotRequestEmail
{
    public function handle($bot_user) {

        $botSendMessage = new BotSendMessage();
        $botUserSetListener = new BotUserSetListener();

        $botSendMessage->handle($bot_user, 'SYS_REQUEST_EMAIL');
        $botUserSetListener->handle('listen_email', 1, $bot_user->id);

        die();
    }
}
