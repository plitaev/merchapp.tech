<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RunStatus extends Model
{
    use HasFactory;

    protected $table = 'run_statuses';

    protected $fillable = [
        'name'
    ];

}
