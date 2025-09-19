<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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

    public function prevois_pay(): BelongsTo
    {
        return $this->belongsTo(Pay::class, 'prevois_pay_id', 'id');
    }

}
