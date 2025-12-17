<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobokassaTax extends Model
{
    use HasFactory;

    protected $table='robokassa_taxes';

    protected $fillable = [
        'code',
        'name'
    ];
}
