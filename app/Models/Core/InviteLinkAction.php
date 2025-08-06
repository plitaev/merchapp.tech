<?php
namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteLinkAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
