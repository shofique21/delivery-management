<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Parcel;
use App\Models\Merchant;
use App\Models\Merchantpayment;
class DashboardController extends Controller
{
    public function index(){
    	return view('backEnd.superadmin.dashboard');
    }
    
    public function bulkpayment(Request $request){
        $selectption = $request->selectptions;
         $total = 0;
        if($selectption==1){
        	$payment = new Merchantpayment();
        	$payment->merchantId = $request->merchantId;
        	$payment->parcelId   = $request->parcelId;
        	$payment->save();
            $parcels_id = $request->parcel_id;
           
            foreach($parcels_id as $parcel_id){
                $parcel =Parcel::find($parcel_id);
                $parcel->paymentInvoice = $payment->id;
                $parcel->merchantPaid = $parcel->merchantAmount;
		    	$parcel->merchantDue = 0;
		    	$parcel->merchantpayStatus = 1;
		    	$parcel->save();
		        $total +=(int)($parcel->cod-($parcel->deliveryCharge+$parcel->codCharge));
		    	
            }
         $totalparcel = count(collect($request)->get('parcel_id'));
         $validMerchant = Merchant::find($request->merchantId);
          
           $url = "http://66.45.237.70/api.php";
                $number="0$validMerchant->phoneNumber";
                $text="A Payment (Invoice No. $payment->id) has been issued of $total Tk where $totalparcel Parcels were processed. Check Invoice on your dashboard. \r\n Regards,\r\n Flingex";
                $data= array(
                'username'=>"01977593593",
                'password'=>"evertech@593",
                'number'=>"$number",
                'message'=>"$text"
                );
                
                $ch = curl_init(); // Initialize cURL
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $smsresult = curl_exec($ch);
                $p = explode("|",$smsresult);
                $sendstatus = $p[0];
            
            
            
            Toastr::success('message', 'Invoice Processing successfully!');
            return redirect('editor/merchant/payment/invoice/'.$request->merchantId);
        }elseif($selectption==0){
            $parcels_id = $request->parcel_id;
            foreach($parcels_id as $parcel_id){
                $parcel         =   Parcel::find($parcel_id);
                $parcel->merchantpayStatus = 0;
		    	$parcel->save();
            
        }
        
         Toastr::success('message', 'Invoice Paid successfully!');
         return redirect()->back();
		}
	}
}
