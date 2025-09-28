<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdamusPaymentMethod extends Model
{
    use HasFactory;

    protected $table='prodamus_payment_methods';

    protected $fillable = [
        'code',
        'name'
    ];
}
