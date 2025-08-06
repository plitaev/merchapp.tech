<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessageAlert;

class BotContacts
{
    public function handle(int $bot_id, $telegram, $webhook) {
        $botSendMessageAlert = new BotSendMessageAlert();
        $botSendMessageAlert->handle($bot_id, $telegram, $webhook, 'SYS_CONTACTS');
    }
}
