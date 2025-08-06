<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiniAppPage extends Model
{
    protected $fillable = [
        'mini_app_id',
        'name',
        'url'
    ];

    public function miniapp(): BelongsTo
    {
        return $this->belongsTo(MiniApp::class, 'mini_app_id', 'id');
    }
}
