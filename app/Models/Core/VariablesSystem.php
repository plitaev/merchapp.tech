<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariablesSystem extends Model
{
    protected $table='variables_systems';

    protected $fillable = [
        'variable_group_id',
        'variable_system_type_id',
        'name',
        'alias',
        'value_string',
        'value_integer',
        'value_date',
        'value_text'
    ];

    public function  variable_system_variable_group(): BelongsTo
    {
        return $this->belongsTo(VariableGroup::class, 'variable_group_id', 'id');
    }

    public function  variable_system_variable_system_type(): BelongsTo
    {
        return $this->belongsTo(VariableSystemType::class, 'variable_system_type_id', 'id');
    }

    public function variables_system_type(): BelongsTo
    {
        return $this->belongsTo(VariableSystemType::class);
    }
}
