<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasOneThrough(Sending::class, BotMessage::class, 'id', 'id', 'bot_message_id', 'sending_id');
    }

}
