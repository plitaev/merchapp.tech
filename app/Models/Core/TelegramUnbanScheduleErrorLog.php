<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TelegramUnbanScheduleErrorLog extends Model
{
    use HasFactory;

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
