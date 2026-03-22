<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TelegramSupergroup extends Model
{
    protected $fillable = [
        'bot_id',
        'name',
        'telegram_id',
        'max_id',
        'give_access',
        'unban',
        'decline_chat_join_request',
        'supergroup_delete_parameter_id',
        'supergroup_delete_days',
        'statistic_recurrent_fail',
        'statistic_user_on_day'
    ];

    public function telegramsupergroup_n(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'bot_id', 'id');
    }

    public function give_access_name(): BelongsTo
    {
        return $this->belongsTo(Boolean::class, 'give_access', 'id');
    }

}
