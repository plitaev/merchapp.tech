<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Bot extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'alias',
        'telegram_token',
        'telegram_webhook',
        'business_connection_id',
        'message_worktime_after_minutes',
        'business_bot_delay_after_bot_sent_message_in_minutes',
        'business_bot_delay_after_operator_sent_message_in_minutes'
    ];

    public function bot_n(): BelongsTo
    {
        return $this->belongsTo(TelegramSupergroup::class, 'telegram_supergroup_id', 'id');
    }

}
