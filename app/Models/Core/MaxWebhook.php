<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaxWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_id',
        'callback'
    ];

}
