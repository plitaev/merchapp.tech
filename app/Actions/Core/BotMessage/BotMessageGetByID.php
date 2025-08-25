<?php
namespace App\Actions\Core\BotMessage;
use App\Models\Core\BotMessage;
class BotMessageGetByID
{
    public function handle(int $id) {
        return BotMessage::find($id);
    }
}
