<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TelegramSendMessageErrorLog extends Model
{
    protected $fillable = [
        'chat_id',
        'bot_message_id',
        'text'
    ];
}
