<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TelegramSendMessageSchedule extends Model
{
    protected $fillable = [
        'sending_id',
        'bot_user_id',
        'chat_id',
        'run_status',
        'send_status',
        'message_id'
    ];
}
