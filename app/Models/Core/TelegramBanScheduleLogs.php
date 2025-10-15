<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class TelegramBanScheduleLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'user_id',
        'chat_id',
        'status'
    ];

    public function bot_user(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

}
