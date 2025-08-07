<?php

namespace App\Models\Core;

class TelegramSendInvoiceErrorLog
{
    protected $fillable = [
        'bot_user_id',
        'chat_id',
        'text'
    ];
}
