<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MaxSendMessageLog extends Model
{
    protected $fillable = [
        'max_user_id',
        'bot_message_id',
        'text',
        'keyboard',
        'max_responce_data'
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
