<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdamusNpdIncomeType extends Model
{
    use HasFactory;

    protected $table='prodamus_npd_income_types';

    protected $fillable = [
        'code',
        'name'
    ];
}
