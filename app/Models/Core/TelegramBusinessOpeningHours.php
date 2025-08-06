<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramBusinessOpeningHours extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'start_minute',
        'end_minute'
    ];
}
