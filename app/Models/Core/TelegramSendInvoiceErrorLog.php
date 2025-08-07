<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class TelegramSendInvoiceErrorLog extends Model
{
    protected $fillable = [
        'bot_user_id',
        'chat_id',
        'text'
    ];
}
