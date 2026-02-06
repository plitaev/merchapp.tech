<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TbankTaxation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name'
    ];
}
