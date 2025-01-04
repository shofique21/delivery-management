<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $table = 'issues';

    protected $fillable = [
        'name' ,'status',
     ];

public function issuedetail(){
    	return $this->hasMany(IssueDetail::class);
    }
}
