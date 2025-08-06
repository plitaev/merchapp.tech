<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BotMessageButtonClick extends Model
{
    protected $fillable = [
        'chat_id',
        'bot_message_button_id'
    ];
}
