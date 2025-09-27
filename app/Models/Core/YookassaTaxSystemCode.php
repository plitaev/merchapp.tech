<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class YookassaTaxSystemCode extends Model
{
    protected $table='yookassa_tax_system_codes';

    protected $fillable = [
        'code',
        'name'
    ];
}
