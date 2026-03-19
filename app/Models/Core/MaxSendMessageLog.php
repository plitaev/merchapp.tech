<?php

namespace App\Models\Core;

class MaxSendMessageLog
{
    protected $fillable = [
        'max_user_id',
        'bot_message_id',
        'text',
        'keyboard'
    ];

    public function bot_message(): BelongsTo
    {
        return $this->belongsTo(BotMessage::class, 'bot_message_id', 'id');
    }

    public function bot_chat(): BelongsTo
    {
        return $this->belongsTo(BotUser::class, 'chat_id', 'telegram_chat_id');
    }
}
