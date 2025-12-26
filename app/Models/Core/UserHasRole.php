<?php
namespace App\Models\Core;

use App\Models\Core\Role;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHasRole extends Model
{
    protected $table = 'user_has_roles';

    protected $fillable = [
        'role_id',
        'model_type',
        'user_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }



}
