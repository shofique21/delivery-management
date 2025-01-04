<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcel extends Model
{
    use SoftDeletes; 
    // protected $casts = [
    //     'status' => 'boolean',
    // ];
   protected $dates = ['deleted_at'];
    
  protected $fillable = ['invoiceNo','user','recipientName','recipientAddress','recipientPhone','merchantId','agentId','merchantAmount','merchantDue','cod','productWeight','note','trackingCode','deliveryCharge','codCharge','orderType','codType','percelType','status','reciveZone','update_by','delivered_at','returnmerchant_date','picked_date'];
 
  public function Parceltype()
      {
         return $this->belongsTo('App\Parceltype');
      }
    
}
