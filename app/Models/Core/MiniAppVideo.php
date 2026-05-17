<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class MiniAppVideo extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'video',
        'audio',
        'date_open',
        'duration',
        'edgecenter_id',
        'edgecenter_name',
        'edgecenter_slug',
        'edgecenter_status',
        'edgecenter_screenshot_url',
        'edgecenter_hls_url',
        'edgecenter_views',
        'other_hls_video_id',
        'other_hls_tracks',
        'other_hls_track_names'
    ];
}
