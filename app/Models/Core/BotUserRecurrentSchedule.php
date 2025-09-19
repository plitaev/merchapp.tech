<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class BotUserRecurrentSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'prevous_pay_id',
        'new_pay_id',
        'recurrent_datetime',
        'run_status',
        'pay_system_responce'
    ];

    public function prevous_pay(): BelongsTo
    {
        return $this->belongsTo(Pay::class, 'prevous_pay_id', 'id');
    }

    public function bot(): HasOneThrough {
        return $this->hasOneThrough(Bot::class, BotUser::class, 'id', 'id', 'bot_user_id', 'bot_id');
    }

}
