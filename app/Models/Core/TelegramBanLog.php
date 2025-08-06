<?php

namespace App\Models\Core;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'status',
    ];
}
