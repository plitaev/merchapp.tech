<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TelegramSendMessageErrorLog extends Model
{
    protected $fillable = [
        'chat_id',
        'bot_message_id',
        'text'
    ];

}
