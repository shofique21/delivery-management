<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecratAgent extends Model
{
    protected $fillable = [
        'user_id','merchant_id', 'commision', 'status',
    ];
}
