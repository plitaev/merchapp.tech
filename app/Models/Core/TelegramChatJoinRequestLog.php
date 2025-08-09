<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TelegramChatJoinRequestLog extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'invite_link',
        'status',
        'telegram_data'
    ];
}
