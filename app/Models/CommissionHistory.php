<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionHistory extends Model
{
    protected $table = 'dm_commission_history';
    protected $fillable = [
        'deliveryman_id','parchel_count','commission_amount', 'status',
    ];
}
