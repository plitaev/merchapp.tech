<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdamusTax extends Model
{
    use HasFactory;

    protected $table='prodamus_taxes';

    protected $fillable = [
        'code',
        'name'
    ];
}
