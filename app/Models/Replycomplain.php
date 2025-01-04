<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replycomplain extends Model
{

    protected $table = 'reply_complains';
    protected $fillable = [
       'user_name' ,'complain_id','details','status',
    ];

}
