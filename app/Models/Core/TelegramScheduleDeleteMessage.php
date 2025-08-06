<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TelegramScheduleDeleteMessage extends Model
{
    protected $fillable = [
        'bot_message_id',
        'telegram_message_id',
        'chat_id',
        'delete_datetime',
        'status',
        'error_text'
    ];

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, BotMessage::class, 'id', 'id', 'bot_message_id', 'bot_id');
    }

}
