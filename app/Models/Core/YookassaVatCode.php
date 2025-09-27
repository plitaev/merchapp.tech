<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class YookassaVatCode extends Model
{
    protected $table='yookassa_vat_codes';

    protected $fillable = [
        'code',
        'name'
    ];
}
