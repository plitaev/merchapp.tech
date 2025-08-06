<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotMessageListener extends Model
{
    protected $fillable = [
        'bot_message_id',
        'listener_id'
    ];

    public function bot_message(): BelongsTo
    {
        return $this->belongsTo(BotMessage::class, 'bot_message_id', 'id');
    }

    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class, 'listener_id', 'id');
    }

}
