<?php
namespace App\Models\Core;

use App\Models\Core\Permission;
use App\Models\Core\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class UserHasPermission extends Model
{
    protected $table = 'user_has_permissions';

    protected $fillable = [
        'permission_id',
        'model_type',
        'user_id',
    ];

    public function permissions(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
