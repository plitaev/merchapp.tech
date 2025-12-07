<?php
namespace App\Models\Core;

use App\Models\Core\Permission;
use App\Models\Core\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';
    
    protected $fillable = [
        'permission_id',
        'model_type',
        'model_id',
    ];

    public function permissions(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function models(): BelongsTo
    {
        return $this->belongsTo(User::class, 'model_id', 'id');
    }
}
