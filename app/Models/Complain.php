<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{

    protected $table = 'complains';
    protected $fillable = [
       'merchantId' ,'subject','type_issue_id','issue_id', 'details','status',
    ];

public function issuedetails(){
    	return $this->hasMany(IssueDetail::class);
    }
}
