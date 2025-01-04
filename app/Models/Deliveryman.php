<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Deliveryman extends Model
{
    
        protected $table = 'deliverymen';

        protected $fillable = ['name','image', 'email', 'password'];

        protected $hidden = ['password', 'remember_token'];
}
