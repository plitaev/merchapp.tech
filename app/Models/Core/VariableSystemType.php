<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class VariableSystemType extends Model
{
    protected $table='variables_system_types';

    protected $fillable = [
        'name'
    ];
}
