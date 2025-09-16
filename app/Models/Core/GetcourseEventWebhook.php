<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class GetcourseEventWebhook extends Model
{
    protected $fillable = [
        'getcourse_id',
        'name',
        'email',
        'event',
        'bot_id'
    ];

}
