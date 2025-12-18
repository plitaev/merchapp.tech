<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobokassaVAT extends Model
{
    use HasFactory;

    protected $table='robokassa_vats';

    protected $fillable = [
        'code',
        'name'
    ];
}
