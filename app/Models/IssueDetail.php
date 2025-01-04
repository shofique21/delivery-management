<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueDetail extends Model
{
    protected $table = 'issue_details';

    protected $fillable = [
        'issue_id','details','status',
     ];

public function issue(){
    	return $this->belongsTo(Issue::class);
    }
}
