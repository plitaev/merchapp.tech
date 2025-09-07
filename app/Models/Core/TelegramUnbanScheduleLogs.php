<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUnbanScheduleLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'user_id',
        'chat_id',
        'status'
    ];
}
