<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TelegramChatMemberErrorLog extends Model
{
    protected $fillable = [
        'bot_user_id',
        'user_id',
        'chat_id',
        'text'
    ];
}
