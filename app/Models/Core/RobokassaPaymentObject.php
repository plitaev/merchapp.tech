<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobokassaPaymentObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name'
    ];
}
