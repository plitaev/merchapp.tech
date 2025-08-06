<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramInviteLinkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_user_id',
        'chat_id',
        'invite_link',
        'telegram_data',
        'invite_link_action_id'
    ];
}
