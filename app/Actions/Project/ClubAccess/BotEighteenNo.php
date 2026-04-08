<?php
namespace App\Actions\Project\ClubAccess;

use App\Actions\Core\BotSendMessage\BotSendMessageAlert;

class BotEighteenNo
{
    public function handle(string $messenger, int $bot_id, $telegram, $webhook) {
        $botSendMessageAlert = new BotSendMessageAlert();

        if ($messenger == 'telegram') $botSendMessageAlert->handle($bot_id, $telegram, $webhook, 'SYS_EIGHTEEN_NO');
    }
}
