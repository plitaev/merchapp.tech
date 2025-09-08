<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Models\Core\BotMessage;
use App\Models\Core\Sending;

class TelegramSendMessageSchedule extends Model
{
    protected $fillable = [
        'sending_id',
        'bot_user_id',
        'run_status',
        'send_status',
        'message_id'
    ];

    public function bot_message(): HasOneThrough {
        return $this->hasOneThrough(BotMessage::class, Sending::class, 'id', 'id', 'sending_id', 'bot_message_id');
    }

}
