<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


class MiniAppVideoTimePoint extends Model
{
    protected $fillable = [
        'mini_app_video_id',
        'name',
        'point'
    ];

}
