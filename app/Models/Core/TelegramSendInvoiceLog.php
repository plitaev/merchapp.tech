<?php

namespace App\Models\Core;

class TelegramSendInvoiceLog
{
    protected $fillable = [
        'bot_user_id',
        'chat_id',
        'invoice'
    ];
}
