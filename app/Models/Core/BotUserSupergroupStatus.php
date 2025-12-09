<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BotUserSupergroupStatus extends Model
{
    protected $fillable = [
        'bot_user_id',
        'telegram_supergroup_id',
        'status'
    ];
}
