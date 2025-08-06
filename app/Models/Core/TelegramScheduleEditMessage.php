<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TelegramScheduleEditMessage extends Model
{
    protected $fillable = [
        'bot_message_id',
        'telegram_message_id',
        'chat_id',
        'edit_datetime',
        'text',
        'entities',
        'keyboard',
        'status',
        'success_text',
        'error_text'
    ];

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, BotMessage::class, 'id', 'id', 'bot_message_id', 'bot_id');
    }

    public function bot_message(): BelongsTo {
        return $this->belongsTo(BotMessage::class, 'bot_message_id', 'id');
    }

}
