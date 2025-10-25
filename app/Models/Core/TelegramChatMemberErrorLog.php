<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TelegramChatMemberErrorLog extends Model
{
    protected $fillable = [
        'bot_user_id',
        'user_id',
        'chat_id',
        'text'
    ];

    public function bot_user(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }
}
