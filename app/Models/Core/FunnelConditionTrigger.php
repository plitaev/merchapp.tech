<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunnelConditionTrigger extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias'
    ];
}
