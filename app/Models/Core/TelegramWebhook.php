<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'callback',
        'update_id',
        'message_id',
        'channel_id',
        'channel_type',
        'channel_title',
        'channel_username',
        'date',
        'text',
        'caption',
        'business_message_chat_id',
        'business_message_from_id',
        'business_message_check_status'
    ];

}
