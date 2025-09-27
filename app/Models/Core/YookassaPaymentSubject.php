<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class YookassaPaymentSubject extends Model
{
    protected $table='yookassa_payment_subjects';

    protected $fillable = [
        'code',
        'name'
    ];
}
