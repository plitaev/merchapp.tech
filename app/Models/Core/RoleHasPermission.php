<?php
namespace App\Models\Core;

use App\Models\Core\Permission;
use App\Models\Core\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleHasPermission extends Model
{
    protected $table='role_has_permissions';

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function permissions(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
