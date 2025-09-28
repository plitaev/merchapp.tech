<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatBotUserOnDay extends Model
{
    use HasFactory;

    protected $table='stat_bot_user_on_day';

    protected $fillable = [
        'bot_id',
        'bot_user_count',
        'stat_date'
    ];
}
