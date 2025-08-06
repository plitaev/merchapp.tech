<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramRevokeInviteLinkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'chat_id',
        'invite_link',
        'telegram_data',
        'text'
    ];
}
