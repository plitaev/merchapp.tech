<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class GetcourseWebhookTicket extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'price'
    ];
}
