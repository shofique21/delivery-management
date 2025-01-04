<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcelnote extends Model
{
     protected $fillable = ['parcelId','user','note','parcelStatus'];
}
