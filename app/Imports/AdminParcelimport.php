<?php
namespace App\Imports;
use App\Models\Codcharge;
use App\Models\Parcel;
use App\Models\Deliverycharge;
use App\Models\Discount;
use App\Models\Merchant;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Session;
use DB;
use Auth;
class AdminParcelimport implements ToModel
{
    
    
  public function model(array $row)
    {
        // return 1;
    // dd($row);
      if (!isset($row[0]) || !isset($row[1]) || !isset($row[2]) || !isset($row[3]) || !isset($row[4]) || !isset($row[5]) || !isset($row[6]) || !isset($row[7]) || !isset($row[8])|| !isset($row[9])) {
            return NULL;
        }
         $merchant=Discount::where('maID',$row[6])->where('delivery_id',$row[7])->first();
         $merchantcod=Merchant::where('id',$row[6])->value('cod');
        //  dd($row[8]);
        $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
        $intialdcharge = Deliverycharge::where('id',$row[7])->first();
        // dd($row[7]);
        $initialcodcharge = Codcharge::where('status',1)->orderBy('id','DESC')->first();
      // fixed delivery charge
     if($row[5]>1 || $row[5]!=NULL){
        $extraweight = (int)$row[5]-1;
        
        $deliverycharge = (($intialdcharge->deliverycharge*1)+($extraweight*$intialdcharge->extradeliverycharge))-$dis;
        $weight = $row[5];
     }else{
        $deliverycharge =($intialdcharge->deliverycharge)-$dis;
       $weight = 1;
     }
     // fixed cod charge
     if($row[2] > 100){
    //    $extracodcharge = 0;
    //    $codcharge = Session::get('codcharge')+$extracodcharge;
    $codcharge = 0;
     }else{
    //   $codcharge= Session::get('codcharge');
    $codcharge = 0;
     }
     $created_time=date('Y-m-d H:i:s');
    
    $all=Parcel::all()->count();     
	 $random = Str::random(4);
    $nu1=$random.rand(20,1000);
    //  dd($row[8]);
    $invoice=$row[8];
        DB::table('parcels')->insertOrIgnore([
           'recipientName'    => $row[0],
           'percelType'       => 1,
           'user'             => Auth::user()->name.'-'.Str::random(1),
           'recipientPhone'   => $row[1],
           'cod'              => $row[2],
           'recipientAddress' => $row[3],
           'agentId'          => $row[4],
           'reciveZone'       => $row[9],
           'productWeight'    => $row[5],
           'merchantId'       => $row[6],
           'invoiceNo'       => "$invoice",
           'created_time'     =>date('Y-m-d H:i:s'),
            'present_date'    =>date("Y-m-d"),
            'created_at'     =>date("Y-m-d"),
           'trackingCode'     => 'B'.$nu1,
           'deliveryCharge'   => $deliverycharge,
           'codCharge'        => ((int)$row[2]*$merchantcod)/100,
           'merchantAmount'   => (int)($row[2])-(int)($deliverycharge+$codcharge),
           'merchantDue'      => (int)$row[2]-(int)($deliverycharge+$codcharge),
           'codType'          => $initialcodcharge->id,
           'orderType'        => $intialdcharge->id,
           'status'           => 1,
        ]);

    }
}




  