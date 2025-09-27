<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class YookassaPaymentMode extends Model
{
    protected $table='yookassa_payment_modes';

    protected $fillable = [
        'code',
        'name'
    ];
}
