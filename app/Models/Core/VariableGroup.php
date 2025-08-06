<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariableGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'alias',
    ];

    public function variable_system(): BelongsTo
    {
        return $this->belongsTo(VariablesSystem::class, 'id', 'variable_group_id');
    }
}
