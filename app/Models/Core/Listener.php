<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Listener extends Model
{
    protected $fillable = [
        'name',
        'alias',
    ];
}
