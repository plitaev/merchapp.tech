<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Sending extends Model
{
    protected $fillable = [
        'bot_message_id',
        'name',
        'user_ban',
        'send_datetime'
    ];
}
