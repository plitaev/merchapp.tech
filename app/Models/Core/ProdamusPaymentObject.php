<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdamusPaymentObject extends Model
{
    use HasFactory;

    protected $table='prodamus_payment_objects';

    protected $fillable = [
        'code',
        'name'
    ];
}
