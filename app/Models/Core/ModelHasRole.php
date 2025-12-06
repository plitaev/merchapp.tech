<?php
namespace App\Models\Core;

use App\Models\Core\Role;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelHasRole extends Model
{
    protected $table = 'model_has_roles';
    public $incrementing = false;

    public $timestamps = false;
    protected $primaryKey = ['role_id','model_id'];

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];


    public function models(): BelongsTo
    {
        return $this->belongsTo(User::class, 'model_id', 'id');
    }

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
