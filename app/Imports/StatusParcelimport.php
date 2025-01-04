<?php
namespace App\Imports;
use App\Models\Parcel;
use Session;
use DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Auth;
class StatusParcelimport implements ToModel
{
    
    
  public function model(array $row)
    {  
        // return 1;
    // dd($row['0']);
      
        $parcel =Parcel::where('trackingCode',@$row[0])->first();
        if(@$parcel->status!=@$row['1']){
            if($row['1']==8){
            $parcel->pcod=$parcel->cod;
          $codcharge=0;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
          $parcel->cod=0;
          
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Auth::user()->name;
          $parcel->save();
            }
        // return DB::table('parcels')->where('trackingCode',$val)
        $parcel->status=$row['1'];
        $parcel->user=$parcel->user.',stu-'.Auth::user()->name;
        // $parcel->update_by=$parcel->update_by.',D-'.Auth::user()->name;
        $parcel->save();
            
        }
         
    }
    }




  