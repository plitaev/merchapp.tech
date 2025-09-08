<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TelegramSendMessageSchedule extends Model
{
    protected $fillable = [
        'sending_id',
        'chat_id',
        'message_photo',
        'message_text',
        'message_keyboard',
        'message_entities',
        'run_status',
        'send_status',
        'message_id'
    ];
}
