<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Sending extends Model
{
    protected $fillable = [
        'bot_id',
        'name',
        'send_date',
        'send_time'
    ];
}
