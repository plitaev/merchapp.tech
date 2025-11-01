<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaySystemCallback extends Model
{
    protected $fillable = [
        'pay_system_id',
        'callback',
        'run_status'
    ];
}
