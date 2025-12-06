<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    protected $table='permissions';

    protected $fillable = [
        'id',
        'name',
        'guard_name'
    ];
}
