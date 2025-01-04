<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Parcel;
use App\Models\Codcharge;
use App\Models\Deliveryman;
use App\Models\Deliverycharge;
use App\Models\Merchantpayment;
use App\Models\Nearestzone;
use App\Imports\AdminParcelimport;
use App\Imports\StatusParcelimport;
use App\Models\Merchant; 
use App\Models\PickDrop;
use App\Models\Discount;
use DB;
use Auth;
use App\Models\Post;
use App\Models\Parcelnote;
use App\Models\Parceltype;
use DataTables;
use Mail;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
class ParcelManageController extends Controller
{
    
public function __construct()
{
    set_time_limit(90000000000);
}
  
  
//   invoice tarck
public function invoiceBarcode(){
    return view('backEnd.addparcel.invoiceTrack');
}

public function ohosogo(){
    // return 1;
    $userData = array("username" => "osgadmin", "password" => "OSGadmin@0987");
$ch = curl_init("https://ohsogo.com/index.php/rest/V1/integration/admin/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Length: " . strlen(json_encode($userData))));

$token = curl_exec($ch);

$ch = curl_init("https://ohsogo.com/index.php/rest/V1/orders/?searchCriteria=all");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . json_decode($token)));

$result = curl_exec($ch);

$result1 = json_decode($result,1);

// $result1['items']->whereRaw('created_at',\Carbon::now()->toTimeString())->get();
dd($result1['items']);
foreach($result1['items'] as $v){
    return @$v['base_subtotal'];
}
echo '<pre>';print_r($result);
}
  public function invoicetrack(Request $request)
    {
        \Artisan::call('schedule:run');

        $parcel = Parcel::where('invoiceNo', $request->invoiceNo)->first();
        // dd($parcel);
    //      return response()->json([
    //       'v' => $request->idelivermanId,
    //   ], 200);
        if ($parcel) {  
                $parcel->status = $request->istatus;
                if ($request->idelivermanId) {
                  $parcel->deliverymanId = $request->idelivermanId;
                }
                $parcel->present_date =date("Y-m-d");
    	        $parcel->updated_time =date('Y-m-d H:i:s');
    	       
                $parcel->save();
                if($request->istatus==4){
            //devivered status 4
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->user = $parcel->user.', d-'.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->delivered_at =date("Y-m-d");
            $parcel->update_by=$parcel->update_by.',D-'.Auth::user()->username;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear Merchant, 
            //   Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is on Deliverd. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
         $number="0$validMerchant->phoneNumber";
 $text="Dear Merchant, Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is on Deliverd. see comment section on Orders. \r\n Regards,\r\n Flingex";
              
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
        }elseif($request->istatus==5){
            //hold status 5
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
             $parcel->user = $parcel->user.', h-'.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
          
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $number1="0$parcel->recipientPhone";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
        $text1="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be  Hold. .\r\n Regards,\r\n Flingex";

        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number1",
        'message'=>"$text1",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
          elseif($request->istatus==8){
              //Return to Marchant status 8
          // $codcharge=$request->customerpay/100;
          $codcharge=0;
           $parcel->user = $parcel->user.', rtm- '.Auth::user()->username;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
        //   $parcel->codCharge=$codcharge;
          $parcel->cod=0;
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Auth::user()->username;
          $parcel->save();
          
          $validMerchant =Merchant::find($parcel->merchantId);
          $deliveryMan = Deliveryman::find($parcel->deliverymanId);
          $readytaka = $parcel->cod+$parcel->dePliveryCharge;
        //   $url = "http://premium.mdlsms.com/smsapi";
        //     $data = [
        //       "api_key" => "C2000829604b00d0ccad46.26595828",
        //       "type" => "text",
        //       "contacts" => "0$parcel->recipientPhone",
        //       "senderid" => "8809612441280",
        //       "msg" => "Dear @$parcel->recipientName \r\nYour parcel from @$validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, 0$deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n PackeN Move",
        //     ];
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     $response = curl_exec($ch);
        //     curl_close($ch);
       }
        elseif($request->istatus==6){
            //Return Pending status 6
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
             $parcel->user = $parcel->user.', rp- '.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
        elseif($request->istatus==3){
            //In Transit status 3
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
             $parcel->user = $parcel->user.', int- '.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            
            $validMerchant =Merchant::find($parcel->merchantId);
            $deliveryMan = Deliveryman::find($parcel->deliverymanId);
            $readytaka = $parcel->cod;
            
              
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$parcel->recipientPhone";
            //     $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, $deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be In Transit. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
         }
         elseif($request->istatus==2){
             //picked status 2
            $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
            $merchantinfo =Merchant::find($parcel->merchantId);
             $parcel->user = $parcel->user.', Pick- '.Auth::user()->username;
            $readytaka = $parcel->cod;
            $parcel->picked_date=date("Y-m-d");
            $parcel->update_by='Pick-'.Auth::user()->username;
            $parcel->save();
            if($deliverymanInfo !=NULL){
            $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'ridername' => $deliverymanInfo->name,
             'riderphone' => $deliverymanInfo->phone,
             'codprice' => $parcel->cod,
             'trackingCode' => $parcel->trackingCode,
            );
    
            $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
               
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Assign Notification');
            });
          }
           $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $merchantinfo->companyName, Tracking ID $parcel->trackingCode will be Picked . Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
         elseif($request->istatus==9){
             //Cancelled status 9
            $merchantinfo =Merchant::find($parcel->merchantId);
             $parcel->user = $parcel->user.', c- '.Auth::user()->username;
             $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'trackingCode' => $parcel->trackingCode,
            );
             $send = Mail::send('frontEnd.emails.percelcancel', $data, function($textmsg) use ($data){
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Cancelled Notification');
            });
        }
                
                $pstatus= Parceltype::where('id',$request->istatus)->first();
                $note = new Parcelnote();
                if($request->istatus==9){
                   $note->note = 'Parcel COD'.$parcel->cod.'Parcel has been '.$pstatus->title .' successfully!'; 
                }
                else{
                   $note->note = 'Parcel has been '.$pstatus->title .' successfully!'; 
                }
                
                $note->parcelId = $parcel->id;
                // $note->user = Session::get('agentName');
                $note->user=Auth::user()->username;
                $note->save();
                
            
                     
            return response()->json([
                'success' => 1,
            ], 200);

        } else {
            return response()->json([
                'success' => 2,
            ], 200);
        }

    }  
// bercode read
 public function track(Request $request)
    {
    $parcel1 = Parcel::where('trackingCode', $request->trackid)->first();
    $parceltype=Parceltype::where('id',$request->status)->first();
     $statuscheck= Parcelnote::where('parcelId',$parcel1->id)->where('parcelStatus',$parceltype->title)->first();
     if($statuscheck){
         return response()->json([
            'success' => 3, 
        ], 200);
     }
     else{


        $parcel = Parcel::where('trackingCode', $request->trackid)->first();
        // dd($parcel);
      //   return response()->json([
      //     'success' => $parcel,
      // ], 200);
        // if ($parcel){
                $parcel->status = $request->status;
                if ($request->delivermanId) {
                  $parcel->deliverymanId = $request->delivermanId;
                }
                $parcel->present_date =date("Y-m-d");
    	        $parcel->updated_time =date('Y-m-d H:i:s');
    	       
                $parcel->save();
                if($request->status==4){
            //devivered status 4
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->user = $parcel->user.', d-'.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->delivered_at =date("Y-m-d");
            $parcel->update_by=$parcel->update_by.',D-'.Auth::user()->username;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear Merchant, 
            //   Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is on Deliverd. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
         $number="0$validMerchant->phoneNumber";
 $text="Dear Merchant, Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is on Deliverd. see comment section on Orders. \r\n Regards,\r\n Flingex";
              
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
        }elseif($request->status==5){
            //hold status 5
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
             $parcel->user = $parcel->user.', h-'.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
          
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $number1="0$parcel->recipientPhone";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
        $text1="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be  Hold. .\r\n Regards,\r\n Flingex";

        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number1",
        'message'=>"$text1",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
          elseif($request->status==8){
              //Return to Marchant status 8
          // $codcharge=$request->customerpay/100;
          $codcharge=0;
           $parcel->user = $parcel->user.', rtm- '.Auth::user()->username;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
        //   $parcel->codCharge=$codcharge;
          $parcel->cod=0;
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Auth::user()->username;
          $parcel->save();
          
          $validMerchant =Merchant::find($parcel->merchantId);
          $deliveryMan = Deliveryman::find($parcel->deliverymanId);
          $readytaka = $parcel->cod+$parcel->dePliveryCharge;
        //   $url = "http://premium.mdlsms.com/smsapi";
        //     $data = [
        //       "api_key" => "C2000829604b00d0ccad46.26595828",
        //       "type" => "text",
        //       "contacts" => "0$parcel->recipientPhone",
        //       "senderid" => "8809612441280",
        //       "msg" => "Dear @$parcel->recipientName \r\nYour parcel from @$validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, 0$deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n PackeN Move",
        //     ];
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     $response = curl_exec($ch);
        //     curl_close($ch);
       }
        elseif($request->status==6){
            //Return Pending status 6
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
             $parcel->user = $parcel->user.', rp- '.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
        elseif($request->status==3){
            //In Transit status 3
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
             $parcel->user = $parcel->user.', int- '.Auth::user()->username;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            
            $validMerchant =Merchant::find($parcel->merchantId);
            $deliveryMan = Deliveryman::find($parcel->deliverymanId);
            $readytaka = $parcel->cod;
            
              
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$parcel->recipientPhone";
            //     $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, $deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be In Transit. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
         }
         elseif($request->status==2){
             //picked status 2
            $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
            $merchantinfo =Merchant::find($parcel->merchantId);
             $parcel->user = $parcel->user.', Pick- '.Auth::user()->username;
            $readytaka = $parcel->cod;
            $parcel->picked_date=date("Y-m-d");
            $parcel->update_by='Pick-'.Auth::user()->username;
            $parcel->save();
            if($deliverymanInfo !=NULL){
            $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'ridername' => $deliverymanInfo->name,
             'riderphone' => $deliverymanInfo->phone,
             'codprice' => $parcel->cod,
             'trackingCode' => $parcel->trackingCode,
            );
    
            $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
               
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Assign Notification');
            });
          }
           $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $merchantinfo->companyName, Tracking ID $parcel->trackingCode will be Picked . Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
         elseif($request->status==9){
             //Cancelled status 9
            $merchantinfo =Merchant::find($parcel->merchantId);
             $parcel->user = $parcel->user.', c- '.Auth::user()->username;
             $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'trackingCode' => $parcel->trackingCode,
            );
             $send = Mail::send('frontEnd.emails.percelcancel', $data, function($textmsg) use ($data){
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Cancelled Notification');
            });
        }
                
                $pstatus= Parceltype::where('id',$request->status)->first();
                $note = new Parcelnote();
                if($request->status==9){
                   $note->note = 'Parcel COD'.$parcel->cod.'Parcel has been '.$pstatus->title .' successfully!'; 
                }
                else{
                   $note->note = 'Parcel has been '.$pstatus->title .' successfully!'; 
                }
                
                $note->parcelId = $parcel->id;
                // $note->user = Session::get('agentName');
                $note->user=Auth::user()->username;
                $note->save();
                
            
                     
            return response()->json([
                'success' => 1,
            ], 200);

        // } else {
            // return response()->json([
            //     'success' => 2,
            // ], 200);
        }

    }  
    
    
    
     // returnToCentralHub
    public function returnToCentralHub(Request $request){
        
        $parceltype = Parceltype::where('slug','return-to-hub')->first();
        // $parceltype= ['title'=>'return-to-central-hub'];
        // dd($parceltype);
        if($request->trackId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
           ->where('parcels.agentId',10)
          ->where('parcels.archive',1)
          ->where('parcels.trackingCode',$request->trackId)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
          ->paginate(500);
       }elseif($request->merchantId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
           ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
           ->where('parcels.agentId',10)
          ->where('parcels.archive',1)
          ->where('parcels.merchantId',$request->merchantId)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->paginate(500);
       $show_data->appends(['merchantId' => $request->merchantId]);
       }elseif($request->phoneNumber!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
           ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
           ->where('parcels.agentId',10)
          ->where('parcels.archive',1)
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
       ->paginate(500);
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
           ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
           ->where('parcels.agentId',10)
          ->where('parcels.archive',1)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
        $show_data->appends(['startDate' => $request->startDate ,'endDate' => $request->endDate]);
       }elseif($request->phoneNumber!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
           ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
           ->where('parcels.agentId',10)
          ->where('parcels.archive',1)
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
       }elseif($request->merchantId!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
           ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
           ->where('parcels.agentId',10)
          ->where('parcels.archive',1)
          ->where('parcels.merchantId',$request->merchantId)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
       }else{
        $show_data = DB::table('parcels')
         ->join('merchants', 'merchants.id','=','parcels.merchantId')
         ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',7)
          ->where('parcels.hubaprove',1)
          ->where('parcels.agentId',10)
         ->where('parcels.archive',1)
         ->orderBy('id','DESC')
         ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
        }
    	return view('backEnd.parcel.parcel',compact('show_data','parceltype'));
    }
    
     public function rider(Request $request){
    //   return response()->json([
    //     'success' => $request->dtrackid,
    // ], 200);
      $parcel = Parcel::where('trackingCode',$request->dtrackid)->first();
      
     
     
      
      // $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
      // $merchantinfo =Merchant::find($parcel->merchantId);
      // $data = array(
      //  'contact_mail' => $merchantinfo->emailAddress,
      //  'ridername' => $deliverymanInfo->name,
      //  'riderphone' => $deliverymanInfo->phone,
      //  'codprice' => $parcel->cod,
      //  'trackingCode' => $parcel->trackingCode,
      // );
      // $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
      //  $textmsg->from('info@flingex.com');
      //  $textmsg->to($data['contact_mail']);
      //  $textmsg->subject('Percel Assign Notification');
      // });
      if($parcel)
      {
        $parcel->deliverymanId = $request->rider;
        $parcel->save();
        $note = new Parcelnote();
        $note->user=Auth::user()->name;
        $note->parcelId = $parcel->id;
        $note->note = "A deliveryman asign successfully!";
        $note->save();
        return response()->json([
        'success' => 1,
    ], 200);

} else {
    return response()->json([
        'success' => 2,
    ], 200);
}
     
    }
   public function parcel(Request $request){
        \Artisan::call('schedule:run');
       $parceltype = Parceltype::where('slug',$request->slug)->first();
        if($request->trackId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.archive',1)
          ->where('parcels.trackingCode',$request->trackId)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
          ->paginate(10);
       }elseif($request->merchantId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.archive',1)
          ->where('parcels.merchantId',$request->merchantId)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->paginate(500);
       $show_data->appends(['merchantId' => $request->merchantId]);
       }elseif($request->phoneNumber!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.archive',1)
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
       ->paginate(500);
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.archive',1)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
        $show_data->appends(['startDate' => $request->startDate ,'endDate' => $request->endDate]);
       }elseif($request->phoneNumber!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.archive',1)
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
       }elseif($request->merchantId!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$parceltype->id)
          ->where('parcels.archive',1)
          ->where('parcels.merchantId',$request->merchantId)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
       }else{
        $show_data = DB::table('parcels')
         ->join('merchants', 'merchants.id','=','parcels.merchantId')
         ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.status',$parceltype->id)
         ->where('parcels.archive',1)
         ->orderBy('id','DESC')
         ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(500);
        }
    	return view('backEnd.parcel.parcel',compact('show_data','parceltype'));
    }
   public function oldparcel(Request $request){
       
        //   exit;
        // $parceltype = Parceltype::where('slug',$request->slug)->first();
         if (request()->ajax()) {
            
            $parceltype = Parceltype::where('slug',$request->slug)->first();
            if ($request->filter_id != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.status',$parceltype->id)
                    // ->where('parcels.trackingCode', $request->trackId)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } 
            elseif ($request->trackId != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.trackingCode', $request->trackId)
                    ->where('parcels.status',$parceltype->id)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->merchantId != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('parcels.status',$parceltype->id)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->phoneNumber != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                ->where('parcels.status',$parceltype->id)
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                ->where('parcels.status',$parceltype->id)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->phoneNumber != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                ->where('parcels.status',$parceltype->id)
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->merchantId != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                ->where('parcels.status',$parceltype->id)
                    ->where('parcels.merchantId', $request->merchantId)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } else {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.status',$parceltype->id)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                    
                ;
            }
            
            return Datatables::of($show_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $pickl='';
                    if($row->pickuploaction==null){
                       $pickl.= @$row->pickLocation;
                    }else{
                        $pickl.= @$row->pickuploaction;
                    }
                    $pnote=Parcelnote::where("parcelId",$row->id)->orderBy("id","DESC")->get();
                    $parcelnote='';
                    foreach($pnote as $pn){
                    $parcelnote.='<tr><td>'.$pn->updated_at.'</td>
                        <td>'.$pn->user.'</td>
                        <td>'.$pn->note.'</td>
                        <td>'.$pn->cnote.'</td></tr>';
                    }
                    $button = '<ul class="action_buttons">                    <li>
                    <button class="edit_icon" href="#" data-toggle="modal"
                        data-target="#merchantParcel'.$row->id.'" title="View"><i
                            class="fa fa-eye"></i></button>
                    <div id="merchantParcel'.$row->id.'" class="modal fade"
                        role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Parcel Details</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Merchant Name</td>
                                                    <td>'.$row->firstName.'
                                                    '.$row->lastName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Phone</td>
                                                    <td>'.$row->phoneNumber.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Email</td>
                                                    <td>'.$row->emailAddress.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Company</td>
                                                    <td>'.@$row->companyName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Pickup Location</td>
                                                    <td>'.$pickl.'</td>
                                                </tr>
                                                <td>Recipient Name</td>
                                                <td>'.$row->recipientName.'</td>
    </tr>
    <tr>
        <td>Recipient Address</td>
        <td>'.$row->recipientAddress.'</td>
    </tr>
    <tr>
        <td>COD</td>
        <td>'.$row->cod.'</td>
    </tr>
    <tr>
        <td>C. Charge</td>
        <td>'.$row->codCharge.'</td>
    </tr>
    <tr>
        <td>D. Charge</td>
        <td>'.$row->deliveryCharge.'</td>
    </tr>
    <tr>
        <td>Sub Total</td>
        <td>'.$row->merchantAmount.'</td>
    </tr>
    <tr>
        <td>Paid</td>
        <td>'.$row->merchantPaid.'</td>
    </tr>
    <tr>
        <td>Due</td>
        <td>'.$row->merchantDue.'</td>
    </tr>
    <tr>
        <td>Last Update</td>
        <td>'.$row->updated_at.'</td>
    </tr>
</table>
</div>
<div class="col-md-6">


<table>
<tr>

    <th>Date</th>
    <th>User </th>
    <th>Note</th>
    <th>Important Note</th>
</tr>

'.$parcelnote.'



</table>

</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<!-- Modal end -->
</li>

                            <li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon"><i class="fa fa-edit"></i></a></li>
                            <li><button class="thumbs_up status" title="Action" data-toggle="modal" sid="' . $row->id . '" statusid="' . $row->status . '" customer_phone="' . $row->recipientPhone . '" data-target="#sUpdateModal"><i class="fa fa-sync-alt"></i></button></li>
                            <li><a class="edit_icon " a href="/editor/parcel/invoice/' . $row->id . '" title="Invoice" target="_blank"><i class="fa fa-file-invoice"></i></a></li>

                             
                            </ul>';

                    return $button;
                })
                 ->addColumn('pickupman', function ($row) {
                    $pickman=Deliveryman::where('id',$row->pickupman_id)->first();
                    $pickupman = '<span>' . @$pickman->name . '</span><br><button class="btn btn-warning picman" data-toggle="modal" data-target="#asignPcikUpModal" pid="'. $row->id .'">Asign</button>';
                    return $pickupman;
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('deliveyrman', function ($row) {
                    $deliveryMan = '<span>' . $row->dname . '</span><br><button type="button" data-toggle="modal" data-target="#asignModal" rid="' . $row->id . '" class="btn-sm btn-info asingd" title="Update Status">asign</button>';
                    return $deliveryMan;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('agent', function ($row) {
                    $agent = '<span>' . $row->hubname . '</span><br><button type="button" data-toggle="modal" data-target="#agentModal" aid="' . $row->id . '" class="btn-sm btn-info asinga" title="Update Status">asign</button>';
                    return $agent;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('bulk', function ($row) {
                    $bulk = '<input type="checkbox" value="' . $row->id . '" name="parcel_id[]" form="myform">';
                    return $bulk;
                })
                ->addColumn('subtotal', function ($row) {
                    if ($row->partial_pay == null) {
                        $partial = 0;
                    } else {
                        $partial = $row->cod - $row->partial_pay;
                    }
                    return (($row->cod - $row->deliveryCharge) - $partial);
                })
                ->addColumn('date', function ($row) {                    
                    $date=date('F d, Y', strtotime($row->updated_at)).' &nbsp &nbsp ( '. date('g:ia', strtotime(@$row->updated_time)).')';
                    return ($date);
                })
                ->rawColumns(['date','bulk', 'pickupman','deliveyrman', 'agent', 'action', 'subtotal'])
                ->make(true);
        }
        // return response()->json([
        //   'result' => view('backEnd.parcel.allparcel', compact('show_data','parceltype'))->render(),
        // ]);
        // return response()->json([
        //     'result' => $show_data,
        //   ]);
        //   exit;
        return view('backEnd.parcel.test1');
    }
     public function move_today($id){
        //  dd($id);
          $date = date('Y-m-d');
          $pic=DB::table('pickups')
    	->where('pickups.id',$id)
    	->select('pickups.*')
    	->update([
    	   'time'=>'12:00',
          'date'=>$date,
          ]);
          
       	Toastr::success('message', ' Move Today Pickup Request!');
    	return redirect()->back();
    
    	
    }
 public function pickup_request(){
      $date = date('Y-m-d');
     $today = DB::table('pickups')
      ->where('pickups.date',$date)
    	->where('pickups.time','<','15:00')
    	->orderBy('pickups.id','DESC')
    	->select('pickups.*')
    	->get();
    	
    	 $tomorrow = DB::table('pickups')
    	 ->where('pickups.date',$date)
    	->where('pickups.time','>','15:00')
    	->orderBy('pickups.id','DESC')
    	->select('pickups.*')
    	->get();
     	return view('backEnd.parcel.pickuprequest',compact('today','tomorrow'));
 }    
 public function manage(){
        $show_data = Nearestzone::
             orderBy('id','DESC')
            ->get();
    	return view('backEnd.nearestzone.manage',compact('show_data'));
    }
      public function dmanage(){
        $show_data = Deliverycharge::
             orderBy('id','DESC')
            ->get();
    	return view('backEnd.deliverycharge.manage',compact('show_data'));
    }
    public function amanage(){
    	$show_datas = DB::table('agents')
    	->join('nearestzones', 'agents.area', '=', 'nearestzones.id' )
    	->select('agents.*', 'nearestzones.zonename')
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.agent.manage',compact('show_datas'));
    }
     public function oldallparcel(Request $request){
        //  \Artisan::call('schedule:run');
          $parceltype = 'All Parcel';
        if($request->trackId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.trackingCode',$request->trackId)
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
          ->get();
       }elseif($request->merchantId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    //   $show_data->appends(['merchantId' => $request->merchantId]);
       return view('backEnd.parcel.mparcel',compact('show_data','parceltype'));
       }elseif($request->phoneNumber!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
       ->get();
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
        // $show_data->appends(['startDate' => $request->startDate ,'endDate' => $request->endDate]);
       }elseif($request->phoneNumber!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->where('parcels.archive',1)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }elseif($request->merchantId!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',1)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }else{
        $show_data = DB::table('parcels')
         ->join('merchants', 'merchants.id','=','parcels.merchantId')
         ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->whereDate('parcels.created_at', '=', date('Y-m-d'))
         ->where('parcels.archive',1)
         ->orderBy('id','DESC')
         ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
        }
        
        
       
        //   $show_data = DB::table('parcels')
        //  ->join('merchants', 'merchants.id','=','parcels.merchantId')
        //  ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        //  ->orderBy('id','DESC')
        //  ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        // ->get();
        return view('backEnd.parcel.allparcel',compact('show_data','parceltype'));
    }
    // all parcel datatable
     public function allparcel(Request $request)
    {
        // $token = "Basic QXBpQXV0aDoqSVRBUEkyMDIyIw==";

        // header("Content-type: text / html; charset = utf-8");
        
        // // $option = array (
        // //     "body" => "Test posting Messages"
        // // );
        
        // $ch = curl_init();
        
        // // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_URL,"http://103.163.246.94/it/apidb/api/allparcel");
        // // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($option));
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Authorization:'. $token));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // $response = curl_exec($ch);
        // curl_close($ch);
        // $data = json_decode($response, true);
        // var_dump(data);exit;
        
        $parceltype = 'All Parcel';
        if (request()->ajax()) {
            if ($request->filter_id != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.archive',1)
                    // ->where('parcels.trackingCode', $request->trackId)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
                $show_data = $data;
            } 
            elseif ($request->trackId != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.trackingCode', $request->trackId)
                    ->where('parcels.archive',1)
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->merchantId != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('parcels.archive',1)
                     ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->status != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.status', $request->status)
                    ->where('parcels.archive',1)
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }  elseif ($request->phoneNumber != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->where('parcels.archive',1)
                    
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->whereBetween('parcels.created_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->where('archive',1)
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->phoneNumber != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->where('archive',1)
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->merchantId != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('archive',1)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } else {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('archive',1)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                    
                ;
            }
            
            return Datatables::of($show_data)
                ->addIndexColumn()
                 ->addColumn('action', function ($row) {
                    $pstatus='';
                    if($row->status == 1){
                        $parceltype=Parceltype::whereIn('id',['2','9'])->get();
                        foreach($parceltype as $ptvalue){
                        $pstatus.='<option value="'.$ptvalue->id.'">'.$ptvalue->title.'</option>';
                        }
                    }else{
                        $parceltype2=Parceltype::get();
                        foreach($parceltype2 as $ptvalue){
                        $pstatus.='<option value="'.$ptvalue->id.'">'.$ptvalue->title.'</option>';
                        }
                    }
                    
                    $pickl='';
                    if($row->pickuploaction==null){
                       $pickl.= @$row->pickLocation;
                    }else{
                        $pickl.= @$row->pickuploaction;
                    }
                    $pnote=Parcelnote::where("parcelId",$row->id)->orderBy("id","DESC")->get();
                    $parcelnote='';
                    
                    foreach($pnote as $pn){
                    $parcelnote.='<tr><td>'.$pn->updated_at.'</td>
                        <td>'.$pn->user.'</td>
                        <td>'.$pn->note.'</td>
                        <td>'.$pn->cnote.'</td></tr>';
                    }
                    $edit='';
                     if($row->status == 4|| $row->status==8){
                    if(Auth::user()->role_id == 1){
                       $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon "><i class="fa fa-edit"></i></a></li>';
                    }else{
                        $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon d-none"><i class="fa fa-edit"></i></a></li>';
                    }
                    }else{
                        $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon "><i class="fa fa-edit"></i></a></li>';
                    }
                    $button = '<ul class="action_buttons"><li>
                    <button class="edit_icon" href="#" data-toggle="modal"
                        data-target="#merchantParcel'.$row->id.'" title="View"><i
                            class="fa fa-eye"></i></button>
                    <div id="merchantParcel'.$row->id.'" class="modal fade"
                        role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Parcel Details</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Merchant Name</td>
                                                    <td>'.$row->firstName.'
                                                    '.$row->lastName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Phone</td>
                                                    <td>'.$row->phoneNumber.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Email</td>
                                                    <td>'.$row->emailAddress.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Company</td>
                                                    <td>'.@$row->companyName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Pickup Location</td>
                                                    <td>'.$pickl.'</td>
                                                </tr>
                                                <td>Recipient Name</td>
                                                <td>'.$row->recipientName.'</td>
    </tr>
    <tr>
        <td>Recipient Address</td>
        <td>'.$row->recipientAddress.'</td>
    </tr>
    <tr>
        <td>COD</td>
        <td>'.$row->cod.'</td>
    </tr>
    <tr>
        <td>C. Charge</td>
        <td>'.$row->codCharge.'</td>
    </tr>
    <tr>
        <td>D. Charge</td>
        <td>'.$row->deliveryCharge.'</td>
    </tr>
    <tr>
        <td>Sub Total</td>
        <td>'.$row->merchantAmount.'</td>
    </tr>
    <tr>
        <td>Paid</td>
        <td>'.$row->merchantPaid.'</td>
    </tr>
    <tr>
        <td>Due</td>
        <td>'.$row->merchantDue.'</td>
    </tr>
    <tr>
        <td>Last Update</td>
        <td>'.$row->updated_at.'</td>
    </tr>
</table>
</div>
<div class="col-md-6">


<table>
<tr>

    <th>Date</th>
    <th>User </th>
    <th>Note</th>
    <th>Important Note</th>
</tr>

'.$parcelnote.'



</table>

</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<!-- Modal end -->
 
                            
                            '.$edit.'
                            
                            <li><button class="thumbs_up status" title="Action" data-toggle="modal" sid="' . $row->id . '" statusids="' . $row->status . '" customer_phone="' . $row->recipientPhone . '" data-target="#sUpdateModal"><i class="fa fa-sync-alt"></i></button></li>
                            <li><a class="edit_icon " a href="/editor/parcel/invoice/' . $row->id . '" title="Invoice" target="_blank"><i class="fa fa-file-invoice"></i></a></li>

                             
                            </ul>';

                    return $button;
                })
                 ->addColumn('pickupman', function ($row) {
                    $pickman=Deliveryman::where('id',$row->pickupman_id)->first();
                    $pickupman = '<span>' . @$pickman->name . '</span><br><button class="btn btn-warning picman" data-toggle="modal" data-target="#asignPcikUpModal" pid="'. $row->id .'">Asign</button>';
                    return $pickupman;
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('deliveyrman', function ($row) {
                    $deliveryMan = '<span>' . $row->dname . '</span><br><button type="button" data-toggle="modal" data-target="#asignModal" rid="' . $row->id . '" class="btn-sm btn-info asingd" title="Update Status">asign</button>';
                    return $deliveryMan;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('agent', function ($row) {
                    $agent = '<span>' . $row->hubname . '</span><br><button type="button" data-toggle="modal" data-target="#agentModal" aid="' . $row->id . '" class="btn-sm btn-info asinga" title="Update Status">asign</button><br><small>'.$row->note.'</small>';
                    return $agent;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('bulk', function ($row) {
                    $bulk = '<input type="checkbox" value="' . $row->id . '" name="parcel_id[]" form="myform">';
                    return $bulk;
                })
                ->addColumn('subtotal', function ($row) {
                    if ($row->partial_pay == null) {
                        $partial = 0;
                    } else {
                        $partial = $row->cod - $row->partial_pay;
                    }
                    return (($row->cod - $row->deliveryCharge) - $partial);
                })
                 ->addColumn('status', function ($row) {
                    if ($row->status == 2) {
                        $status = '<span class="btn btn-sm btn-info ">'.$row->pstatus.'</span>';
                    } 
                     elseif ($row->status == 3) {
                        $status = '<span class="btn btn-sm btn-primary">'.$row->pstatus.'</span>';
                    }
                     elseif ($row->status == 4) {
                        $status = '<span class="btn btn-sm btn-success">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 5) {
                        $status = '<span class="btn btn-sm btn-dark">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 9) {
                        $status = '<span class="btn btn-sm btn-danger ">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 6) {
                        $status = '<span class="btn btn-sm btn-danger rounded-circle h3">'.$row->pstatus.'</span>';
                    }else {
                        $status = '<span class="btn btn-sm btn-warning">'.$row->pstatus.'</span>';
                    }
                    return  $status;
                })
                ->addColumn('date', function ($row) {                    
                    $date=date('F d, Y', strtotime($row->updated_at)).' &nbsp &nbsp ( '. date('g:ia', strtotime(@$row->updated_time)).')';
                    return ($date);
                })
                 ->addColumn('softdelete', function ($row) {
                     if(
                     Auth::user()->role_id == 1){
                      $softDelete = '<button type="button" data-toggle="modal" data-target="#softDelete" pDeleteid="'. $row->id . '" class="btn-sm btn-danger softdelete" title="Parcel Delete">Delete</button>';  
                      return $softDelete;
                     }
                   
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                
                ->rawColumns(['date','bulk', 'pickupman','deliveyrman', 'agent', 'action', 'subtotal','status','softdelete'])
                ->make(true);
        }
        // return response()->json([
        //   'result' => view('backEnd.parcel.allparcel', compact('show_data','parceltype'))->render(),
        // ]);
        // return response()->json([
        //     'result' => $show_data,
        //   ]);
        //   exit;
        return view('backEnd.parcel.test');
    }
    // insite dhaka
     public function insitedhaka(Request $request)
    {
        $parceltype = 'All Parcel instedhaka';
        if (request()->ajax()) {
            if ($request->filter_id != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.status','!=',9)
                    ->where('parcels.orderType',6)
                    // ->where('parcels.trackingCode', $request->trackId)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } 
            elseif ($request->trackId != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.trackingCode', $request->trackId)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->merchantId != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->status != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.status', $request->status)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }  elseif ($request->phoneNumber != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->whereBetween('parcels.created_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC') 
                    ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->where('archive',1)
                    
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->phoneNumber != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->where('archive',1)
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->merchantId != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } else {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(3))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',6)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                    
                ;
            }
            
            return Datatables::of($show_data)
                ->addIndexColumn()
                 ->addColumn('action', function ($row) {
                    $pstatus='';
                    if($row->status == 1){
                        $parceltype=Parceltype::whereIn('id',['2','9'])->get();
                        foreach($parceltype as $ptvalue){
                        $pstatus.='<option value="'.$ptvalue->id.'">'.$ptvalue->title.'</option>';
                        }
                    }else{
                        $parceltype2=Parceltype::get();
                        foreach($parceltype2 as $ptvalue){
                        $pstatus.='<option value="'.$ptvalue->id.'">'.$ptvalue->title.'</option>';
                        }
                    }
                    
                    $pickl='';
                    if($row->pickuploaction==null){
                       $pickl.= @$row->pickLocation;
                    }else{
                        $pickl.= @$row->pickuploaction;
                    }
                    $pnote=Parcelnote::where("parcelId",$row->id)->orderBy("id","DESC")->get();
                    $parcelnote='';
                    
                    foreach($pnote as $pn){
                    $parcelnote.='<tr><td>'.$pn->updated_at.'</td>
                        <td>'.$pn->user.'</td>
                        <td>'.$pn->note.'</td>
                        <td>'.$pn->cnote.'</td></tr>';
                    }
                    $edit='';
                     if($row->status == 4|| $row->status==8){
                    if(Auth::user()->role_id == 1){
                       $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon "><i class="fa fa-edit"></i></a></li>';
                    }else{
                        $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon d-none"><i class="fa fa-edit"></i></a></li>';
                    }
                    }else{
                        $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon "><i class="fa fa-edit"></i></a></li>';
                    }
                    $button = '<ul class="action_buttons"><li>
                    <button class="edit_icon" href="#" data-toggle="modal"
                        data-target="#merchantParcel'.$row->id.'" title="View"><i
                            class="fa fa-eye"></i></button>
                    <div id="merchantParcel'.$row->id.'" class="modal fade"
                        role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Parcel Details</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Merchant Name</td>
                                                    <td>'.$row->firstName.'
                                                    '.$row->lastName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Phone</td>
                                                    <td>'.$row->phoneNumber.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Email</td>
                                                    <td>'.$row->emailAddress.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Company</td>
                                                    <td>'.@$row->companyName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Pickup Location</td>
                                                    <td>'.$pickl.'</td>
                                                </tr>
                                                <td>Recipient Name</td>
                                                <td>'.$row->recipientName.'</td>
    </tr>
    <tr>
        <td>Recipient Address</td>
        <td>'.$row->recipientAddress.'</td>
    </tr>
    <tr>
        <td>COD</td>
        <td>'.$row->cod.'</td>
    </tr>
    <tr>
        <td>C. Charge</td>
        <td>'.$row->codCharge.'</td>
    </tr>
    <tr>
        <td>D. Charge</td>
        <td>'.$row->deliveryCharge.'</td>
    </tr>
    <tr>
        <td>Sub Total</td>
        <td>'.$row->merchantAmount.'</td>
    </tr>
    <tr>
        <td>Paid</td>
        <td>'.$row->merchantPaid.'</td>
    </tr>
    <tr>
        <td>Due</td>
        <td>'.$row->merchantDue.'</td>
    </tr>
    <tr>
        <td>Last Update</td>
        <td>'.$row->updated_at.'</td>
    </tr>
</table>
</div>
<div class="col-md-6">


<table>
<tr>

    <th>Date</th>
    <th>User </th>
    <th>Note</th>
    <th>Important Note</th>
</tr>

'.$parcelnote.'



</table>

</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<!-- Modal end -->
 
                            
                            '.$edit.'
                            
                            <li><button class="thumbs_up status" title="Action" data-toggle="modal" sid="' . $row->id . '" statusids="' . $row->status . '" customer_phone="' . $row->recipientPhone . '" data-target="#sUpdateModal"><i class="fa fa-sync-alt"></i></button></li>
                            <li><a class="edit_icon " a href="/editor/parcel/invoice/' . $row->id . '" title="Invoice" target="_blank"><i class="fa fa-file-invoice"></i></a></li>

                             
                            </ul>';

                    return $button;
                })
                 ->addColumn('pickupman', function ($row) {
                    $pickman=Deliveryman::where('id',$row->pickupman_id)->first();
                    $pickupman = '<span>' . @$pickman->name . '</span><br><button class="btn btn-warning picman" data-toggle="modal" data-target="#asignPcikUpModal" pid="'. $row->id .'">Asign</button>';
                    return $pickupman;
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('deliveyrman', function ($row) {
                    $deliveryMan = '<span>' . $row->dname . '</span><br><button type="button" data-toggle="modal" data-target="#asignModal" rid="' . $row->id . '" class="btn-sm btn-info asingd" title="Update Status">asign</button>';
                    return $deliveryMan;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('agent', function ($row) {
                    $agent = '<span>' . $row->hubname . '</span><br><button type="button" data-toggle="modal" data-target="#agentModal" aid="' . $row->id . '" class="btn-sm btn-info asinga" title="Update Status">asign</button>';
                    return $agent;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('bulk', function ($row) {
                    $bulk = '<input type="checkbox" value="' . $row->id . '" name="parcel_id[]" form="myform">';
                    return $bulk;
                })
                ->addColumn('subtotal', function ($row) {
                    if ($row->partial_pay == null) {
                        $partial = 0;
                    } else {
                        $partial = $row->cod - $row->partial_pay;
                    }
                    return (($row->cod - $row->deliveryCharge) - $partial);
                })
                 ->addColumn('status', function ($row) {
                    if ($row->status == 2) {
                        $status = '<span class="btn btn-sm btn-info ">'.$row->pstatus.'</span>';
                    } 
                     elseif ($row->status == 3) {
                        $status = '<span class="btn btn-sm btn-primary">'.$row->pstatus.'</span>';
                    }
                     elseif ($row->status == 4) {
                        $status = '<span class="btn btn-sm btn-success">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 5) {
                        $status = '<span class="btn btn-sm btn-dark">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 9) {
                        $status = '<span class="btn btn-sm btn-danger ">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 6) {
                        $status = '<span class="btn btn-sm btn-danger rounded-circle h3">'.$row->pstatus.'</span>';
                    }else {
                        $status = '<span class="btn btn-sm btn-warning">'.$row->pstatus.'</span>';
                    }
                    return  $status;
                })
                ->addColumn('date', function ($row) {                    
                    $date=date('F d, Y', strtotime($row->updated_at)).' &nbsp &nbsp ( '. date('g:ia', strtotime(@$row->updated_time)).')';
                    return ($date);
                })
                 ->addColumn('softdelete', function ($row) {
                     if(
                     Auth::user()->role_id == 1){
                      $softDelete = '<button type="button" data-toggle="modal" data-target="#softDelete" pDeleteid="'. $row->id . '" class="btn-sm btn-danger softdelete" title="Parcel Delete">Delete</button>';  
                      return $softDelete;
                     }
                   
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                
                ->rawColumns(['date','bulk', 'pickupman','deliveyrman', 'agent', 'action', 'subtotal','status','softdelete'])
                ->make(true);
        }
        // return response()->json([
        //   'result' => view('backEnd.parcel.allparcel', compact('show_data','parceltype'))->render(),
        // ]);
        // return response()->json([
        //     'result' => $show_data,
        //   ]);
        //   exit;
        return view('backEnd.parcel.insitedhaka');
    }
    // outsite dhaka
     public function outsitedhaka(Request $request)
    {
        $parceltype = 'All Parcel Outsite Dhaka';
        if (request()->ajax()) {
            if ($request->filter_id != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.status','!=',9)
                    ->where('parcels.orderType',5)
                    // ->where('parcels.trackingCode', $request->trackId)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } 
            elseif ($request->trackId != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.trackingCode', $request->trackId)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->merchantId != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }elseif ($request->status != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.status', $request->status)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            }  elseif ($request->phoneNumber != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->whereBetween('parcels.created_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC') 
                    ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->where('archive',1)
                    
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->phoneNumber != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.recipientPhone', $request->phoneNumber)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->where('archive',1)
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName','merchants.pickLocation', 'merchants.lastName', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } elseif ($request->merchantId != null && $request->startDate != null && $request->endDate != null) {
                $show_data = DB::table('parcels')
                ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('parcels.merchantId', $request->merchantId)
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->whereBetween('parcels.updated_at', [$request->startDate, $request->endDate])
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                ;
            } else {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    ->where('archive',1)
                     ->whereDate('parcels.created_at', \Carbon::now()->subDays(5))
                    ->where('parcels.status','!=',4)
                    ->where('parcels.status','!=',8)
                    ->where('parcels.orderType',5)
                    ->orderBy('id', 'DESC')
                    ->select('parcels.*', 'deliverymen.name as dname', 'agents.name as hubname', 'parceltypes.title as pstatus', 'nearestzones.zonename', 'merchants.firstName', 'merchants.lastName','merchants.pickLocation', 'merchants.phoneNumber', 'merchants.emailAddress', 'merchants.companyName as marchantName', 'merchants.status as mstatus', 'merchants.id as mid')
                    
                ;
            }
            
            return Datatables::of($show_data)
                ->addIndexColumn()
                 ->addColumn('action', function ($row) {
                    $pstatus='';
                    if($row->status == 1){
                        $parceltype=Parceltype::whereIn('id',['2','9'])->get();
                        foreach($parceltype as $ptvalue){
                        $pstatus.='<option value="'.$ptvalue->id.'">'.$ptvalue->title.'</option>';
                        }
                    }else{
                        $parceltype2=Parceltype::get();
                        foreach($parceltype2 as $ptvalue){
                        $pstatus.='<option value="'.$ptvalue->id.'">'.$ptvalue->title.'</option>';
                        }
                    }
                    
                    $pickl='';
                    if($row->pickuploaction==null){
                       $pickl.= @$row->pickLocation;
                    }else{
                        $pickl.= @$row->pickuploaction;
                    }
                    $pnote=Parcelnote::where("parcelId",$row->id)->orderBy("id","DESC")->get();
                    $parcelnote='';
                    
                    foreach($pnote as $pn){
                    $parcelnote.='<tr><td>'.$pn->updated_at.'</td>
                        <td>'.$pn->user.'</td>
                        <td>'.$pn->note.'</td>
                        <td>'.$pn->cnote.'</td></tr>';
                    }
                    $edit='';
                     if($row->status == 4|| $row->status==8){
                    if(Auth::user()->role_id == 1){
                       $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon "><i class="fa fa-edit"></i></a></li>';
                    }else{
                        $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon d-none"><i class="fa fa-edit"></i></a></li>';
                    }
                    }else{
                        $edit.= '<li><a href="/editor/parcel/edit/'. $row->id .'" class="edit_icon "><i class="fa fa-edit"></i></a></li>';
                    }
                    $button = '<ul class="action_buttons"><li>
                    <button class="edit_icon" href="#" data-toggle="modal"
                        data-target="#merchantParcel'.$row->id.'" title="View"><i
                            class="fa fa-eye"></i></button>
                    <div id="merchantParcel'.$row->id.'" class="modal fade"
                        role="dialog">
                        <div class="modal-dialog modal-lg">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Parcel Details</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Merchant Name</td>
                                                    <td>'.$row->firstName.'
                                                    '.$row->lastName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Phone</td>
                                                    <td>'.$row->phoneNumber.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Merchant Email</td>
                                                    <td>'.$row->emailAddress.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Company</td>
                                                    <td>'.@$row->companyName.'</td>
                                                </tr>
                                                <tr>
                                                    <td>Pickup Location</td>
                                                    <td>'.$pickl.'</td>
                                                </tr>
                                                <td>Recipient Name</td>
                                                <td>'.$row->recipientName.'</td>
    </tr>
    <tr>
        <td>Recipient Address</td>
        <td>'.$row->recipientAddress.'</td>
    </tr>
    <tr>
        <td>COD</td>
        <td>'.$row->cod.'</td>
    </tr>
    <tr>
        <td>C. Charge</td>
        <td>'.$row->codCharge.'</td>
    </tr>
    <tr>
        <td>D. Charge</td>
        <td>'.$row->deliveryCharge.'</td>
    </tr>
    <tr>
        <td>Sub Total</td>
        <td>'.$row->merchantAmount.'</td>
    </tr>
    <tr>
        <td>Paid</td>
        <td>'.$row->merchantPaid.'</td>
    </tr>
    <tr>
        <td>Due</td>
        <td>'.$row->merchantDue.'</td>
    </tr>
    <tr>
        <td>Last Update</td>
        <td>'.$row->updated_at.'</td>
    </tr>
</table>
</div>
<div class="col-md-6">


<table>
<tr>

    <th>Date</th>
    <th>User </th>
    <th>Note</th>
    <th>Important Note</th>
</tr>

'.$parcelnote.'



</table>

</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<!-- Modal end -->
 
                            
                            '.$edit.'
                            
                            <li><button class="thumbs_up status" title="Action" data-toggle="modal" sid="' . $row->id . '" statusids="' . $row->status . '" customer_phone="' . $row->recipientPhone . '" data-target="#sUpdateModal"><i class="fa fa-sync-alt"></i></button></li>
                            <li><a class="edit_icon " a href="/editor/parcel/invoice/' . $row->id . '" title="Invoice" target="_blank"><i class="fa fa-file-invoice"></i></a></li>

                             
                            </ul>';

                    return $button;
                })
                 ->addColumn('pickupman', function ($row) {
                    $pickman=Deliveryman::where('id',$row->pickupman_id)->first();
                    $pickupman = '<span>' . @$pickman->name . '</span><br><button class="btn btn-warning picman" data-toggle="modal" data-target="#asignPcikUpModal" pid="'. $row->id .'">Asign</button>';
                    return $pickupman;
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('deliveyrman', function ($row) {
                    $deliveryMan = '<span>' . $row->dname . '</span><br><button type="button" data-toggle="modal" data-target="#asignModal" rid="' . $row->id . '" class="btn-sm btn-info asingd" title="Update Status">asign</button>';
                    return $deliveryMan;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('agent', function ($row) {
                    $agent = '<span>' . $row->hubname . '</span><br><button type="button" data-toggle="modal" data-target="#agentModal" aid="' . $row->id . '" class="btn-sm btn-info asinga" title="Update Status">asign</button>';
                    return $agent;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('bulk', function ($row) {
                    $bulk = '<input type="checkbox" value="' . $row->id . '" name="parcel_id[]" form="myform">';
                    return $bulk;
                })
                ->addColumn('subtotal', function ($row) {
                    if ($row->partial_pay == null) {
                        $partial = 0;
                    } else {
                        $partial = $row->cod - $row->partial_pay;
                    }
                    return (($row->cod - $row->deliveryCharge) - $partial);
                })
                 ->addColumn('status', function ($row) {
                    if ($row->status == 2) {
                        $status = '<span class="btn btn-sm btn-info ">'.$row->pstatus.'</span>';
                    } 
                     elseif ($row->status == 3) {
                        $status = '<span class="btn btn-sm btn-primary">'.$row->pstatus.'</span>';
                    }
                     elseif ($row->status == 4) {
                        $status = '<span class="btn btn-sm btn-success">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 5) {
                        $status = '<span class="btn btn-sm btn-dark">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 9) {
                        $status = '<span class="btn btn-sm btn-danger ">'.$row->pstatus.'</span>';
                    }
                    elseif ($row->status == 6) {
                        $status = '<span class="btn btn-sm btn-danger rounded-circle h3">'.$row->pstatus.'</span>';
                    }else {
                        $status = '<span class="btn btn-sm btn-warning">'.$row->pstatus.'</span>';
                    }
                    return  $status;
                })
                ->addColumn('date', function ($row) {                    
                    $date=date('F d, Y', strtotime($row->updated_at)).' &nbsp &nbsp ( '. date('g:ia', strtotime(@$row->updated_time)).')';
                    return ($date);
                })
                 ->addColumn('softdelete', function ($row) {
                     if(
                     Auth::user()->role_id == 1){
                      $softDelete = '<button type="button" data-toggle="modal" data-target="#softDelete" pDeleteid="'. $row->id . '" class="btn-sm btn-danger softdelete" title="Parcel Delete">Delete</button>';  
                      return $softDelete;
                     }
                   
                    
                    // return ($row->cod-$row->deliveryCharge);
                })
                
                ->rawColumns(['date','bulk', 'pickupman','deliveyrman', 'agent', 'action', 'subtotal','status','softdelete'])
                ->make(true);
        }
        // return response()->json([
        //   'result' => view('backEnd.parcel.allparcel', compact('show_data','parceltype'))->render(),
        // ]);
        // return response()->json([
        //     'result' => $show_data,
        //   ]);
        //   exit;
        return view('backEnd.parcel.outsitedhaka');
    }
 public function deliverymanasign1(Request $request)
    {
        // return response($request->all());
        // $this->validate($request,[
        // 'deliverymanId'=>'required',
        // ]);
        $parcel = Parcel::find($request->hidden_id);
        if ($parcel) {
            $parcel->deliverymanId = $request->deliverymanId;
            $parcel->save();

            if ($request->note) {
                $note = new Parcelnote();
                $note->parcelId = $request->hidden_id;
                $note->note = $request->note;
                $note->save();
            }

            return response()->json([
                'success' => 1,
            ], 200);

        } else {
            return response()->json([
                'success' => 2,
            ], 200);
        }
        $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
        $merchantinfo = Merchant::find($parcel->merchantId);
        $data = array(
            'contact_mail' => $merchantinfo->emailAddress,
            'ridername' => $deliverymanInfo->name,
            'riderphone' => $deliverymanInfo->phone,
            'codprice' => $parcel->cod,
            'trackingCode' => $parcel->trackingCode,
        );
        $send = Mail::send('frontEnd.emails.percelassign', $data, function ($textmsg) use ($data) {
            $textmsg->from('info@flingex.com');
            $textmsg->to($data['contact_mail']);
            $textmsg->subject('Percel Assign Notification');
        });

        // Toastr::success('message', 'A deliveryman asign successfully!');
        // return response()->json(['success' => true]);

    }
     public function archiveparcel_create(Request $request){
         if($request->parcel_id){
           
            //  dd($parcel); 
        $parcels_id = $request->parcel_id;
        
           foreach($parcels_id as $id){
                $parcel =Parcel::find($id);
                  $parcel->archive=2;
                $parcel->save();
                 
        }
             
       }
         $status=Parceltype::get();
          $merchants = Merchant::orderBy('id','DESC')->get();
         
          $parceltype = 'Archive Parcel Create';
     if($request->merchantId!=NULL  &&  $request->status!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.status',$request->status)
          ->where('parcels.archive',1)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
        
       }
       elseif( $request->merchantId!=NULL && $request->status!=NULL  ){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$request->status)
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
        //  $show_data->appends(['merchantId' => $request->merchantId,'status'=>$request->status]);
       }
        elseif($request->merchantId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    //   $show_data->appends(['merchantId' => $request->merchantId]);
    //   return view('backEnd.parcel.mparcel',compact('show_data','parceltype'));
       }elseif($request->status!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.status',$request->status)
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
       ->get();
       }
       elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
        // $show_data->appends(['startDate' => $request->startDate ,'endDate' => $request->endDate]);
       }
       elseif($request->merchantId!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',1)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->get();
       }else{
        $show_data = DB::table('parcels')
         ->join('merchants', 'merchants.id','=','parcels.merchantId')
         ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         
         ->where('parcels.archive',1)
         ->orderBy('id','DESC')
         ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->take(10)->get();
        }
        
        
       
        //   $show_data = DB::table('parcels')
        //  ->join('merchants', 'merchants.id','=','parcels.merchantId')
        //  ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        //  ->orderBy('id','DESC')
        //  ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        // ->get();
        return view('backEnd.parcel.archive_create',compact('show_data','parceltype','status','merchants'));
    }
     public function archiveparcel(Request $request){
        //  if($request->archive_startDate!=NULL && $request->archive_endDate!=NULL){
        //      $parcel =Parcel::whereBetween('parcels.created_at',[$request->archive_startDate, $request->archive_endDate])->get();
        //     //  dd($parcel);
        //      foreach($parcel as $pa){
        //          $pa->archive=2;
        //          $pa->save();
                 
        //      }
             
        //  }
         
         
          $parceltype = 'Archive Parcel';
        if($request->trackId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.trackingCode',$request->trackId)
          ->where('parcels.archive',2)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
          ->paginate(10);
       }elseif($request->merchantId!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',2)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->paginate(10);
       $show_data->appends(['merchantId' => $request->merchantId]);
       return view('backEnd.parcel.mparcel',compact('show_data','parceltype'));
       }elseif($request->phoneNumber!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->where('parcels.archive',2)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
       ->paginate(10);
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->where('parcels.archive',2)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(10);
        $show_data->appends(['startDate' => $request->startDate ,'endDate' => $request->endDate]);
       }elseif($request->phoneNumber!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.recipientPhone',$request->phoneNumber)
          ->where('parcels.archive',2)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(10);
       }elseif($request->merchantId!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->merchantId)
          ->where('parcels.archive',2)
          ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(10);
       }else{
        $show_data = DB::table('parcels')
         ->join('merchants', 'merchants.id','=','parcels.merchantId')
         ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         
         ->where('parcels.archive',2)
         ->orderBy('id','DESC')
         ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.pickLocation','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->paginate(50);
        }
        
        
       
        //   $show_data = DB::table('parcels')
        //  ->join('merchants', 'merchants.id','=','parcels.merchantId')
        //  ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        //  ->orderBy('id','DESC')
        //  ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        // ->get();
        return view('backEnd.parcel.archive',compact('show_data','parceltype'));
    }
    public function restore($id){
        $parcel =Parcel::where('id',$id)->first();
        $parcel->archive=1;
        $parcel->save();
        Toastr::success('message', 'Parcel Restore Has Been Successfully!');
    	return redirect()->back();
    }
     public function extra(){
$parcel=[
"BBST4632",
"BYcf9311",
"B3yab314",
"B6Zsk997",
"BWKjD24",
"BzxZV262",
"BkxUl964",
"B6fYw72",
"BAAdU69",
"BuFem730",
"BHKFf737",
"BzBOP773",
"BtRew585",
"BweXh989",
"BzagK712",
"BF6uv677",
"Bqxmi688",
"BtZen735",
"BSMNE463",
"BX7p848",
"BlArN451",
"BPsde542",
"BpSDr148",
"ByobZ241",
"BhWI620",
"BE8Pq71",
"Bo4wb485",
"BsJof926",
"B0CMF520",
"BFjR5931",
"BOBpF59",
"BVVls287",
"BH684248",
"BwTnj276",
"BfToC745",
"BA0yf979",
"BVUYa202",
"BjTWc248",
"BvpB7951",
"BZszw290",
"BhWfK161",
"BUAfj590",
"By3lj433",
"BTBcX554",
"B4m2N847",
"Be47O548",
"BOCY340",
"BM0k5752",
"Bv7s7761",
"Bnq4h741",
"BrmPa712",
"BjSE3526",
"BKj3n94",
"BzQrL899",
"Bi7RI206",
"B8dMg298",
"B1JLr591",
"BAGlO58",
"BJbGT217",
"BxlCn140",
"BRpzp419",
"BSV2E620",
"B1HiM569",
"B6AIv948",
"BR8eN740",
"BtssL872",
"BNEu8377",
"BBtyp28",
"BT30N632",
"BZ31R876",
"BEypA429",
"Bth7l891",
"BBsCI260",
"Bcszq691",
"BJ2yA770",
"Mb1lo6",
"BX85Q516",
"BWWEV896",
"BRd33755",
"Bk9Pu689",
"BkJrv627",
"ByOwi61",
"BMWkL897",
"BryOK894",
"BhN7N254",
"Bq2gJ588",
"B0Oho43",
"BHuHT533",
"BohDC239",
"BCP8r311",
"BwRAK910",
"BWsrw39",
"BW270914",
"BmRqA37",
"BnmV152",
"BJ600600",
"BXdgO929",
"Bplra230",
"BOnEJ947",
"BFiur551",
"B07Dn125",
"BR9er229",
"B2vT1632",
"MbfSg2",
"BAql4102",
"BEXTH983",
"B5prs954",
"B2LYM479",
"Bj5d0814",
"Bgaw2315",
"BrvEV69",
"Bcjhs154",
"Bxcho50",
"BQqv6267",
"BwW1j654",
"B9GRj194",
"BduBA89",
"BQwR9526",
"BN4zQ620",
"BzBmx806",
"BEQ29160",
"BH7tI511",
"B3ZA149",
"BkV7u113",
"BsPCY297",
"BxFCc256",
"BCKMd973",
"BHf7a402",
"Bp6A0118",
"Br0FM944",
"BefEJ45",
"BzhhC842",
"BOtCK145",
"BdyZy465",
"BUt2c792",
"BLZiG662",
"BDJ8o500",
"ByOrQ153",
"BhvB0370",
"B3TkY734",
"Bot8v747",
"BiupB204",
"B5WM330",
"BC0FK409",
"BNLXz82",
"BhjOs616",
"BGhBb440",
"Bcurx115",
"MgHay16",
"BPQ1d348",
"BW0Zw309",
"B9HKo811",
"BuyEF233",
"BeyNN320",
"BN80h776",
"BCd9H881",
"BxJvf40",
"Ba7Ek661",
"B5O42162",
"Bi7oO864",
"BRqPl714",
"BCNJB63",
"BDGl7274",
"BUGp8817",
"BQTXj602",
"B74xy914",
"BgAON603",
"BEqeR44",
"BoVf7786",
"BnxvJ632",
"BRS6K364",
"BHwGD256",
"B1tQd519",
"BdrMr906",
"BkPCI407",
"Bqx2i354",
"BdyyH691",
"B7Tve287",
"BxleK777",
"BUNxB749",
"BmkGM720",
"BlVOi977",
"BGz2b153",
"BSKXI488",
"BBR5g448",
"BHcxi945",
"BuMxO965",
"BLLAy897",
];
        //  $parcel =Parcel::whereBetween('created_at',['2022-04-06', '2022-04-06'])->where('merchantId','909')->delete();
        //  var_dump($parcel);
        //  dd($parcel);
        // $parcel =Parcel::whereBetween('created_at',['2021-01-01', '2022-01-31'])->where('archive',1)->get();
         foreach($parcel as $x => $val){
            
                 $p=DB::table('parcels')->where('trackingCode',$val)->update([
                     'status'=>6,
                    ]);
                //  $p->delete() ;
                 
             }
        // $a=DB::table('arichives')->count();
        // find the duplicate ids first.


// $parcel = DB::table('parcels')->where('parcels.archive','2')->delete();

// Now delete and exclude those min ids.

        // dd($parcel);
        //   $parcel = DB::table('parcels')->where('parcels.archive','2')->get();  
        //   foreach($parcel as $v){
        //       DB::table('arichives')->insert([
        //           'user'=> $v->user,
        //           'invoiceNo'=> $v->invoiceNo,
        //           'merchantId'=> $v->merchantId,
        //           'paymentInvoice'=> $v->paymentInvoice,
        //           'cod'=> $v->cod,
        //           'merchantAmount'=> $v->merchantAmount,
        //           'merchantDue'=> $v->merchantDue,
        //           'merchantpayStatus'=> $v->merchantpayStatus,
        //           'merchantPaid'=> $v->merchantPaid,
        //           'recipientName'=> $v->recipientName,
        //           'recipientAddress'=> $v->recipientAddress,
        //           'recipientPhone'=> $v->recipientPhone,
        //           'note'=> $v->note,
        //           'deliveryCharge'=> $v->deliveryCharge,
        //           'codCharge'=> $v->codCharge,
        //           'productPrice'=> $v->productPrice,
        //           'deliverymanId'=> $v->deliverymanId,
        //           'pickupman_id'=> $v->pickupman_id,
        //           'agentId'=> $v->agentId,
        //           'productWeight'=> $v->productWeight,
        //           'percelType'=> $v->percelType,
        //           'helpNumber'=> $v->helpNumber,
        //           'reciveZone'=> $v->reciveZone,
        //           'pickuploaction'=> $v->pickuploaction,
        //           'orderType'=> $v->orderType,
        //           'codType'=> $v->codType,
        //           'status'=> $v->status,
        //           'withdrawal'=> $v->withdrawal,
        //           'partial_pay'=> $v->partial_pay,
        //           'agentAprove'=> $v->agentAprove,
        //           'hubaprove'=> $v->hubaprove,
        //           'dmanaprove'=> $v->dmanaprove,
        //           'pmanaprove'=> $v->pmanaprove,
        //           'present_date'=> $v->present_date,
        //           'update_by'=> $v->update_by,
        //           'picked_date'=> $v->picked_date,
        //           'returnmerchant_date'=> $v->returnmerchant_date,
        //           'created_at'=> $v->created_at,
        //           'updated_at'=> $v->updated_at,
        //           'created_time'=> $v->created_time,
        //           'updated_time'=> $v->updated_time,
        //           'delivered_at'=> $v->delivered_at,
        //           'archive'=> $v->archive,
        //           'pcod'=> $v->pcod,
        //           'deleted_at'=> $v->deleted_at,
                  
        //           ]);
        //   }
        //   dd($parcel);
        Toastr::success('message', 'Parcel Aechive Has Been Successfully!');
    	return redirect()->back();
    }
    public function marchant_percel(Request $request){
        // dd($request);
      $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.recipientPhone','LIKE','%'.$request->phone."%")
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
       ->get();
       return view('backEnd.parcel.mparcel',compact('show_data'));
   
    }
    public function invoice($id){
    	    $show_data = DB::table('parcels')
    	    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    	  
    	    ->where('parcels.id',$id)
    	    ->select('parcels.trackingCode','parcels.cod','parcels.recipientName','parcels.recipientPhone','parcels.recipientAddress','parcels.agentId','parcels.created_at','parcels.invoiceNo','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
            ->first();
    	return view('backEnd.parcel.invoice',compact('show_data'));
    }
    
    public function partial_pay(Request $request){
        $parcel = Parcel::find($request->id);
        
    //   $nu1=9999999+$all+11;
      $store_parcel = new Parcel;
     $store_parcel->invoiceNo = $parcel->invoiceNo;
     $store_parcel->user = Auth::user()->name;
     $store_parcel->agentId = $parcel->agentId;
     $store_parcel->merchantId = $parcel->merchantId;
     $store_parcel->percelType = $parcel->percelType;
     $store_parcel->reciveZone = $parcel->reciveZone;
     $store_parcel->deliverymanId = $parcel->deliverymanId;
     $store_parcel->agentId = $parcel->agentId;
     $store_parcel->cod =$parcel->cod-$request->partial_pay;
     $store_parcel->recipientName = $parcel->recipientName;
     $store_parcel->recipientAddress = $parcel->recipientAddress;
     $store_parcel->pickuploaction = $parcel->pickuploaction;
     $store_parcel->recipientPhone = $parcel->recipientPhone;
     $store_parcel->productWeight = $parcel->productWeight;
     $store_parcel->trackingCode  = $parcel->trackingCode;
     $store_parcel->note = $parcel->note;
     $store_parcel->deliveryCharge = 0;
     $store_parcel->codCharge = 0;
     $store_parcel->created_time=date('Y-m-d H:i:s');
     $store_parcel->present_date =date("Y-m-d");
     $store_parcel->merchantAmount = $parcel->cod-$request->partial_pay;
     $store_parcel->merchantDue = $parcel->cod-$request->partial_pay;
     $store_parcel->orderType = $parcel->orderType;
     $store_parcel->codType = $parcel->codType;
     $store_parcel->status = 6;
    //  return $store_parcel;
             $store_parcel->save();
            $note = new Parcelnote();
            $note->parcelId = $store_parcel->id;
            $note->mid=$store_parcel->merchantId;
            $note->note = 'Total Cod '.$parcel->cod. '  Partial Price'.$request->partial_pay;;
            $note->parcelStatus = 'Return Pending';
            $note->user=Auth::user()->name;
            $parcel->status = 4;
            $note->save();
            
            $parcel->cod=$request->partial_pay;
        $parcel->user=$parcel->user.','.Auth::user()->name;
        $parcel->trackingCode  = $parcel->trackingCode."-P";
        $parcel->merchantAmount = $request->partial_pay-$parcel->deliveryCharge;
        $parcel->merchantDue = $request->partial_pay-$parcel->deliveryCharge;
    	$parcel->save();
    	
    	    $note1 = new Parcelnote();
            $note1->parcelId = $parcel->id;  
            $note1->mid=$parcel->merchantId;
            $note1->note = 'Partial Parcel Delivered successfully ';
            $note1->parcelStatus = 'Partial';
            $note1->user=Auth::user()->name;
            $note1->save();
        
    	$parcel->save();  Toastr::success('message', 'Partial Pay Add successfully!');
    	return redirect()->back();

    }

       public function agentasign(Request $request){
     $this->validate($request, [
            'agentId' => 'required',
        ]);
        $parcel = Parcel::find($request->hidden_id);
        if ($parcel) {
         
                $note = new Parcelnote();
                $note->user=Auth::user()->name;
                $note->parcelId = $request->hidden_id;
                $note->note= "Asign agent Successfully !"; 
                if ($request->cnote) { $note->cnote = $request->cnote; }
                else{ }
                $note->save();
                
            $parcel->agentId = $request->agentId;
            $parcel->save();
           
            return response()->json([
                'success' => 1,
            ], 200);

        } else {
            return response()->json([
                'success' => 2,
            ], 200);
        }

    }
    
     public function pickupmanasign(Request $request){
    //   $this->validate($request,[
    //     'pickupmanId'=>'required',
    //   ]);
      $parcel = Parcel::find($request->hidden_id);
      $parcel->pickupman_id = $request->pickupmanId;
      $parcel->save();

      if($request->note){
            $note = new Parcelnote();
             $note->user=Auth::user()->name;
            $note->parcelId = $request->hidden_id;
            $note->note = $request->note;
            $note->save();
        }
        return response()->json([
                'success' => 1,
            ], 200);
    //   Toastr::success('message', 'A PickUp Man asign successfully!');
    //   return redirect()->back();
        
  }
  
    public function deliverymanasign(Request $request){
      $this->validate($request,[
        'deliverymanId'=>'required',
      ]);
      $parcel = Parcel::find($request->hidden_id);
      $parcel->deliverymanId = $request->deliverymanId;
      $parcel->save();

      if($request->note){
            $note = new Parcelnote();
             $note->user=Auth::user()->name;
            $note->parcelId = $request->hidden_id;
            $note->note = $request->note;
            $note->save();
        }

      Toastr::success('message', 'A deliveryman asign successfully!');
      return redirect()->back();
      $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
      $merchantinfo =Merchant::find($parcel->merchantId);
      $data = array(
       'contact_mail' => $merchantinfo->emailAddress,
       'ridername' => $deliverymanInfo->name,
       'riderphone' => $deliverymanInfo->phone,
       'codprice' => $parcel->cod,
       'trackingCode' => $parcel->trackingCode,
      );
      $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
       $textmsg->from('info@flingex.com');
       $textmsg->to($data['contact_mail']);
       $textmsg->subject('Percel Assign Notification');
      });
        
  }
    public function statusupdate(Request $request){
        // dd($request);
    	$this->validate($request,[
         // 'status'=>'required',
    	]); 
     $parceltype=Parceltype::where('id',$request->pstatus)->first();
    //  $statuscheck= Parcelnote::where('parcelId',$request->hidden_id)->where('parcelStatus',$parceltype->title)->first();
    //  if($statuscheck){
    //      return response()->json([
    //         'success' => 3, 
    //     ], 200);
    //  }
    //  else{
        $parcel = Parcel::where('id',$request->hidden_id)->first();
        if($request->pstatus==4){
            //devivered status 4
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->delivered_at=date("Y-m-d");
        	$parcel->update_by=$parcel->update_by.',D-'.Auth::user()->name;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear Merchant, 
            //   Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is on Deliverd. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
         $number="0$validMerchant->phoneNumber";
 $text="Dear Merchant, Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is Delivered. See comment section on Orders. \r\n Regards,\r\n Flingex";
              
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
        }elseif($request->pstatus==5){
            //hold status 5
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
          
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $number1="0$parcel->recipientPhone";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
        $text1="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be  Hold by. .\r\n Regards,\r\n Flingex";

        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number1",
        'message'=>"$text1",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
          elseif($request->pstatus==8){
              //Return to Marchant status 8
          // $codcharge=$request->customerpay/100;
          $parcel->pcod=$parcel->cod;
          $codcharge=0;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
        //   $parcel->codCharge=$codcharge;
          $parcel->cod=0;
          
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Auth::user()->name;
          $parcel->save();
         
            
          
        //   $validMerchant =Merchant::find($parcel->merchantId);
        //   $deliveryMan = Deliveryman::find($parcel->deliverymanId);
        //   $readytaka = $parcel->cod+$parcel->deliveryCharge;
        //   $url = "http://premium.mdlsms.com/smsapi";
        //     $data = [
        //       "api_key" => "C2000829604b00d0ccad46.26595828",
        //       "type" => "text",
        //       "contacts" => "0$parcel->recipientPhone",
        //       "senderid" => "8809612441280",
        //       "msg" => "Dear @$parcel->recipientName \r\nYour parcel from @$validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, 0$deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n PackeN Move",
        //     ];
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     $response = curl_exec($ch);
        //     curl_close($ch);
       }
        elseif($request->pstatus==6){
            //Return Pending status 6
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
        elseif($request->pstatus==3){
            //In Transit status 3
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            
            $validMerchant =Merchant::find($parcel->merchantId);
            $deliveryMan = Deliveryman::find($parcel->deliverymanId);
            $readytaka = $parcel->cod;
            
              
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$parcel->recipientPhone";
            //     $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, $deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be In Transit . Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
         }
         elseif($request->pstatus==2){
             //picked status 2
            //  return 1;
            $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
            $merchantinfo =Merchant::find($parcel->merchantId);
            $readytaka = $parcel->cod;
            	$parcel->picked_date=date("Y-m-d");
            	$parcel->update_by='Pick-'.Auth::user()->name;
            	$parcel->save();
            if($deliverymanInfo !=NULL){
            $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'ridername' => $deliverymanInfo->name,
             'riderphone' => $deliverymanInfo->phone,
             'codprice' => $parcel->cod,
             'trackingCode' => $parcel->trackingCode,
            );
    
            $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
               
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Assign Notification');
            });
          }
           $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $merchantinfo->companyName, Tracking ID $parcel->trackingCode will be Picked by . Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
         elseif($request->pstatus==9){
             //Cancelled status 9
            $merchantinfo =Merchant::find($parcel->merchantId);
             $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'trackingCode' => $parcel->trackingCode,
            );
            //  $send = Mail::send('frontEnd.emails.percelcancel', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@flingex.com');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Percel Cancelled Notification');
            // });
        }
        
    	
    	$parcel->status = (int)$request->pstatus;
    	$parcel->present_date =date("Y-m-d");
    	$parcel->updated_time =date('Y-m-d H:i:s');
    	$parcel->user=$parcel->user.','.Auth::user()->name;
    	$parcel->save();

             
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;            
            $note->note = $request->snote;
            $note->mid = $parcel->merchantId;
            $note->cnote = $request->note;
            $note->eta = $request->eta;
            $note->parcelStatus = $parceltype->title;
            $note->user=Auth::user()->name;
            $note->save();
        
    	 return response()->json([
            'success' => 1,
        ], 200);
     
         
    //  }
    }
     public function statusupdatetwo(Request $request){
        // dd($request);
    	$this->validate($request,[
    		'status'=>'required',
    	]); 
    	$parceltype=Parceltype::where('id',$request->status)->first();
    	$parcel = Parcel::find($request->hidden_id);
    	$parcel->status = $request->status;
    	$parcel->present_date =date("Y-m-d");
    	$parcel->updated_time =date('Y-m-d H:i:s');
    	$parcel->user=Auth::user()->name;
    	$parcel->save();

             
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;            
            $note->note = $request->snote;
            $note->cnote = $request->note;
            $note->mid = $parcel->merchantId;
            $note->parcelStatus = $parceltype->title;
            $note->user=Auth::user()->name;
            $note->save();
        
     
         
     

        if($request->status==4){
            //devivered status 4
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->delivered_at =date("Y-m-d");
        	$parcel->update_by=	$parcel->update_by.',D-'.Auth::user()->name;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear Merchant, 
            //   Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is on Deliverd. see comment section on Orders. \r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
         $number="0$validMerchant->phoneNumber";
 $text="Dear Merchant, Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber is Delivered. See comment section on Orders. \r\n Regards,\r\n Flingex";
              
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
        }elseif($request->status==5){
            //hold status 5
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
          
            
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $number1="0$parcel->recipientPhone";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $parcel->recipientName , $parcel->recipientPhone is on Hold. see comment section on Orders. \r\n Regards,\r\n Flingex";
        $text1="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be  Hold. .\r\n Regards,\r\n Flingex";

        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number1",
        'message'=>"$text1",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
          elseif($request->status==8){
              //Return to Marchant status 8
          // $codcharge=$request->customerpay/100;
          $codcharge=0;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
        //   $parcel->codCharge=$codcharge;
          $parcel->cod=0;
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Auth::user()->username;
          $parcel->save();
          
          $validMerchant =Merchant::find($parcel->merchantId);
          $deliveryMan = Deliveryman::find($parcel->deliverymanId);
          $readytaka = $parcel->cod+$parcel->deliveryCharge;
        //   $url = "http://premium.mdlsms.com/smsapi";
        //     $data = [
        //       "api_key" => "C2000829604b00d0ccad46.26595828",
        //       "type" => "text",
        //       "contacts" => "0$parcel->recipientPhone",
        //       "senderid" => "8809612441280",
        //       "msg" => "Dear @$parcel->recipientName \r\nYour parcel from @$validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, 0$deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n PackeN Move",
        //     ];
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     $response = curl_exec($ch);
        //     curl_close($ch);
       }
        elseif($request->status==6){
            //Return Pending status 6
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            $validMerchant =Merchant::find($parcel->merchantId);
            // $url = "http://66.45.237.70/api.php";
            //     $number="0$validMerchant->phoneNumber";
            //     $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="0$validMerchant->phoneNumber";
        $text="Dear $validMerchant->companyName \r\n Your Parcel Tracking ID $parcel->trackingCode for $validMerchant->companyName , $validMerchant->phoneNumber will be return within 48 hours. see comment section on Orders.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
        elseif($request->status==3){
            //In Transit status 3
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            
            $validMerchant =Merchant::find($parcel->merchantId);
            $deliveryMan = Deliveryman::find($parcel->deliverymanId);
            $readytaka = $parcel->cod;
            
              
            //   $url = "http://66.45.237.70/api.php";
            //     $number="0$parcel->recipientPhone";
            //     $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be delivered by $deliveryMan->name, $deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
            //     $data= array(
            //     'username'=>"01977593593",
            //     'password'=>"evertech@593",
            //     'number'=>"$number",
            //     'message'=>"$text"
            //     );
                
            //     $ch = curl_init(); // Initialize cURL
            //     curl_setopt($ch, CURLOPT_URL,$url);
            //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     $smsresult = curl_exec($ch);
            //     $p = explode("|",$smsresult);
            //     $sendstatus = $p[0];
            
             $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $validMerchant->companyName, Tracking ID $parcel->trackingCode will be In Transit by $deliveryMan->name, $deliveryMan->phone. Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
              
         }
         elseif($request->status==2){
             //picked status 2
            $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
            $merchantinfo =Merchant::find($parcel->merchantId);
            $readytaka = $parcel->cod;
            $parcel->picked_date=date("Y-m-d");
            $parcel->update_by='Pick-'.Auth::user()->username;
            $parcel->save();
            if($deliverymanInfo !=NULL){
            $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'ridername' => $deliverymanInfo->name,
             'riderphone' => $deliverymanInfo->phone,
             'codprice' => $parcel->cod,
             'trackingCode' => $parcel->trackingCode,
            );
    
            $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
               
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Assign Notification');
            });
          }
           $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
                 $number="0$parcel->recipientPhone";
                $text="Dear $parcel->recipientName \r\nYour parcel from $merchantinfo->companyName, Tracking ID $parcel->trackingCode will be Picked . Please keep TK. $readytaka ready.\r\n Regards,\r\n Flingex";
        
        $data= array(
        'masking'=>"Flingex",
        'userName'=>"Flingex",
        'password'=>"b57d05174707d151a9369e79af41a5c5",
      
        'receiver'=>"$number",
        'message'=>"$text",
        );
        
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/",$smsresult);
        $sendstatus = $p[0];
        }
         elseif($request->status==9){
             //Cancelled status 9
            $merchantinfo =Merchant::find($parcel->merchantId);
             $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'trackingCode' => $parcel->trackingCode,
            );
            //  $send = Mail::send('frontEnd.emails.percelcancel', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@flingex.com');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Percel Cancelled Notification');
            // });
        }
    // 	 return response()->json([
    //         'success' => 1,
    //     ], 200);
     Toastr::success('message', 'Status Update successfully!');
     return redirect()->back();
    }

    public function create(){
        $merchants = Merchant::where('status',1)->orderBy('id','DESC')->get();
          $areas = Nearestzone::where('status',1)->get();
        $delivery=Deliverycharge::where('status',1)->get();
        return view('backEnd.addparcel.create',compact('merchants','delivery','areas'));
    }
    // parcel report
      public function parcelreport(Request $request){
          
          $paid=0;
          $deliveryCharged=0;
         
    if($request->mid=='Allmarcent'){
//  return 2;
         $merchantInvoice = Merchantpayment::whereDate('created_at','>=',$request->startDate)->whereDate('created_at','<=',$request->endDate)->orderBy('id','DESC')->get();
         foreach($merchantInvoice as $key=>$value1){
            // $paid += Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->sum('cod');
            $deliveryCharged+=Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->sum('deliveryCharge');
            $totalinvoice = Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->count();
         }
        //   dd($paid);
        $id=$request->mid;
      $merchants = Merchant::orderBy('id','DESC')->get();
      $parcelr =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $paid =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('merchantpayStatus',1)->where('archive',1)->sum('cod');
    // $paid =Parcel::where('merchantpayStatus',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $unpaid =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantDue');
   
    $parcelpictd =Parcel::where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpriceCOD =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
// dd($parcelprice);
    // $deliveryCharge =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->sum('deliveryCharge');
     $deliveryCharge =0;
    // $deliveryCharged= Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->whereIn('status',4)->sum('deliveryCharge');

    // $codCharge= Parcel::whereBetween('updated_at', [$request->startDate, $request->endDate])->where('status',4)->sum('codCharge');
    $codCharge= 0;
    // $Collectedamount =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('cod');
    $Collectedamount =0;
	$all=Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->where('status',4)->get();
			foreach($all as $p){
			    $deliveryCharge+=$p->deliveryCharge;
			    $codCharge+=$p->codCharge;
			    $Collectedamount+=$p->cod;
			    
			}
    $parcelcount =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    }else{
              
    $id=$request->mid;
   
          
    $merchants = Merchant::orderBy('id','DESC')->get();
    if($request->startDate && $request->endDate){
     $merchantInvoice = Merchantpayment::where('merchantId',$request->mid)->whereDate('created_at','>=',$request->startDate)->whereDate('created_at','<=',$request->endDate)->orderBy('id','DESC')->get();
         foreach($merchantInvoice as $key=>$value1){
            // $paid += Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->sum('cod');
            // $deliveryCharged+=Parcel::where('paymentInvoice',$value1->id)->sum('deliveryCharge');
            $totalinvoice = Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->count();
         }
    
    }
    $parcelr =Parcel::where('merchantId',$request->mid)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('merchantId',$request->mid)->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('merchantId',$request->mid)->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('merchantId',$request->mid)->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $paid =Parcel::where('status',4)->where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('merchantpayStatus',1)->sum('cod');
    // $unpaid =Parcel::where('status',4)->where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('merchantDue');
     $deliveryCharge =Parcel::where('merchantId',$request->mid)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
    $parcelpictd =Parcel::where('merchantId',$request->mid)->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('merchantId',$request->mid)->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('merchantId',$request->mid)->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('merchantId',$request->mid)->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('merchantId',$request->mid)->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpriceCOD =Parcel::where('merchantId',$request->mid)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
// dd($parcelprice);
    $deliveryCharged= $parcelprice =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->sum('deliveryCharge');

    $codCharge= $parcelprice =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->sum('codCharge');

    $Collectedamount =Parcel::where('merchantId',$request->mid)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');

    $parcelcount =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    }

    return view('backEnd.addparcel.report')->with('parcelr',$parcelr)->with('deliveryCharge',$deliveryCharge)->with('deliveryCharged',$deliveryCharged)->with('paid',$paid)->with('unpaid',@$unpaid)->with('parcelcount',$parcelcount)->with('parcelc',$parcelc)->with('parcelpriceCOD',$parcelpriceCOD)->with('parcelpa',$parcelpa)->with('parcelre',$parcelre)->with('merchants',$merchants)->with('id',$id)->with('parcelpictd',$parcelpictd)->with('parcelinterjit',$parcelinterjit)->with('parcelhold',$parcelhold)->with('parcelrrtupa',$parcelrrtupa)->with('parcelrrhub',$parcelrrhub)->with('deliveryCharge',$deliveryCharge)->with('codCharge',$codCharge)->with('Collectedamount',$Collectedamount);
    }
    // merchant report
     public function report(Request $request){
    if($request->mid=='Allmarcent'){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('deliverycharges', 'parcels.orderType','=','deliverycharges.id')
          ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','deliverycharges.title as zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    //   dd($show_data);
        $id=$request->mid;
      $merchants = Merchant::orderBy('id','DESC')->get();
      $parcelr =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpictd =Parcel::where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpriceCOD =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
    $paid=0;
    $merchantInvoice = Merchantpayment::whereDate('created_at','>=',$request->startDate)->whereDate('created_at','<=',$request->endDate)->orderBy('id','DESC')->get();
         foreach($merchantInvoice as $key=>$value1){
            // $paid += Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->sum('cod');
            // $deliveryCharged+=Parcel::where('paymentInvoice',$value1->id)->sum('deliveryCharge');
            $totalinvoice = Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->count();
         }
    
    
    $paid =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('merchantpayStatus',1)->sum('cod');
    $unpaid =Parcel::whereIn('status',[4,8])->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantDue');
// dd($paid);
    // $deliveryCharge= Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->sum('deliveryCharge');
    $deliveryCharged = Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->sum('deliveryCharge');
    // $codCharge= Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->sum('codCharge');

    // $Collectedamount =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('cod');
 $deliveryCharge =0;
    // $deliveryCharged= Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->whereIn('status',4)->sum('deliveryCharge');

    // $codCharge= Parcel::whereBetween('updated_at', [$request->startDate, $request->endDate])->where('status',4)->sum('codCharge');
    $codCharge= 0;
    // $Collectedamount =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('cod');
    $Collectedamount =0;
	$all=Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->get();
			foreach($all as $p){
			    $deliveryCharge+=(int)$p->deliveryCharge;
			    $codCharge+=(int)$p->codCharge;
			    $Collectedamount+=(int)$p->cod;
			    
			}
    $parcelcount =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    }else{
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('deliverycharges', 'parcels.orderType','=','deliverycharges.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','deliverycharges.title as zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get()->take(50);
      
    $id=$request->mid;
      $paid=0;
      if($request->startDate && $request->endDate){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('deliverycharges', 'parcels.orderType','=','deliverycharges.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','deliverycharges.title as zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
      $merchantInvoice = Merchantpayment::where('merchantId',$request->mid)->whereDate('created_at','>=',$request->startDate)->whereDate('created_at','<=',$request->endDate)->orderBy('id','DESC')->get();
         foreach($merchantInvoice as $key=>$value1){
            // $paid += Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->sum('cod');
            // $deliveryCharged+=Parcel::where('paymentInvoice',$value1->id)->sum('deliveryCharge');
            $totalinvoice = Parcel::where('paymentInvoice',$value1->id)->where('archive',1)->count();
         }
      }
     $paid =Parcel::where('merchantId',$request->mid)->where('status',4)->where('merchantpayStatus',1)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('cod');
    $unpaid =Parcel::where('merchantId',$request->mid)->where('status',4)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantDue');
    $merchants = Merchant::orderBy('id','DESC')->get();
    $parcelr =Parcel::where('merchantId',$request->mid)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('merchantId',$request->mid)->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('merchantId',$request->mid)->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('merchantId',$request->mid)->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpictd =Parcel::where('merchantId',$request->mid)->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('merchantId',$request->mid)->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('merchantId',$request->mid)->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('merchantId',$request->mid)->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('merchantId',$request->mid)->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpriceCOD =Parcel::where('merchantId',$request->mid)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
// dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('deliveryCharge');

    $codCharge= $parcelprice =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('codCharge');

    $Collectedamount =Parcel::where('merchantId',$request->mid)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
 $deliveryCharged = Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->sum('deliveryCharge');
    $parcelcount =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    }

    return view('backEnd.addparcel.marcentreport')->with('show_data',$show_data)->with('paid',$paid)->with('deliveryCharged',$deliveryCharged)->with('unpaid',$unpaid)->with('parcelr',$parcelr)->with('parcelcount',$parcelcount)->with('parcelc',$parcelc)->with('parcelpriceCOD',$parcelpriceCOD)->with('parcelpa',$parcelpa)->with('parcelre',$parcelre)->with('merchants',$merchants)->with('id',$id)->with('parcelpictd',$parcelpictd)->with('parcelinterjit',$parcelinterjit)->with('parcelhold',$parcelhold)->with('parcelrrtupa',$parcelrrtupa)->with('parcelrrhub',$parcelrrhub)->with('deliveryCharge',$deliveryCharge)->with('codCharge',$codCharge)->with('Collectedamount',$Collectedamount);
    }
    public function parcelstore(Request $request){
        // dd($request);
      $this->validate($request,[
        'cod'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
        'weight'=>'required|numeric|min:1|max:20',
      ]);
      
      $merchant=Discount::where('maID',$request->merchantId)->where('delivery_id',$request->daytype)->first();
      $merchantcod=Merchant::where('id',$request->merchantId)->value('cod');
    //   dd($merchantcod);
      
      $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
      $hub=Nearestzone::where('id',$request->reciveZone)->first();
     
     // fixed delivery charge
     $intialdcharge = Deliverycharge::find($request->daytype);
     $initialcodcharge = Codcharge::where('status',1)->orderBy('id','DESC')->first();
     if($request->weight > 1 || $request->weight !=NULL){
      $extraweight = $request->weight-1;
       $deliverycharge = (int)(($intialdcharge->deliverycharge*1)+($extraweight*$intialdcharge->extradeliverycharge))-$dis;
       $weight = $request->weight;
     }else{
      $deliverycharge = (int)($intialdcharge->deliverycharge-$dis);
      $weight = 1;
      
     }
   
     // fixed cod charge
     if($request->cod > 100){
    //   $extracod=$request->cod -100;
    //   $extracodcharge = $extracod/100;
    $extracodcharge = 0;
       $codcharge = (int)$initialcodcharge->codcharge+$extracodcharge;
       
     }else{
       $codcharge= $initialcodcharge->codcharge;
      
     }
//   return 2;
    $all=Parcel::all()->count();
	$random = Str::random(3);
    $nu1=$all;
	
     $store_parcel = new Parcel;
     $store_parcel->invoiceNo = $request->invoiceNo;
     $store_parcel->user = Auth::user()->name;
     $store_parcel->agentId = $hub->hub_id;
     $store_parcel->merchantId = $request->merchantId;
     $store_parcel->percelType = $request->percelType;
     $store_parcel->reciveZone = $request->reciveZone;
     $store_parcel->cod = $request->cod;
     $store_parcel->recipientName = $request->name;
     $store_parcel->recipientAddress = $request->address;
     $store_parcel->pickuploaction = $request->pickuploaction;
     $store_parcel->recipientPhone = $request->phonenumber;
     $store_parcel->productWeight = $weight;
     $store_parcel->trackingCode  = 'A'.$random.rand(2,50).$nu1;
     $store_parcel->note = $request->note;
     $store_parcel->deliveryCharge = $deliverycharge;
     $store_parcel->codCharge =($request->cod*$merchantcod)/100;
     $store_parcel->created_time=date('Y-m-d H:i:s');
     $store_parcel->present_date =date("Y-m-d");
     $store_parcel->merchantAmount = (int)($request->cod)-($deliverycharge);
     $store_parcel->merchantDue = (int)($request->cod)-($deliverycharge);
     $store_parcel->orderType = $intialdcharge->id;
     $store_parcel->codType = $initialcodcharge->id;
     $store_parcel->status = 1;
    //  return $store_parcel;
     $store_parcel->save();
     
            $note = new Parcelnote();
            $note->parcelId = $store_parcel->id;
            $note->mid=$store_parcel->merchantId;
            $note->note = 'Parcel Create successfully ';
            $note->parcelStatus = 'Pending';
            $note->user=Auth::user()->name;
            $note->save();
     
     
     Toastr::success('Success!', 'Thanks! your parcel add successfully');
     return redirect('editor/parcel/create');
  } 

  public function parceledit($id){
    $edit_data = Parcel::find($id);
    $merchants = Merchant::orderBy('id','DESC')->get();
    $delivery=Deliverycharge::where('status',1)->get();
    return view('backEnd.addparcel.edit',compact('edit_data','merchants','delivery'));
  }
  public function parcelupdate(Request $request){
     $this->validate($request,[
            'cod'=>'required',
            'name'=>'required',
            'percelType'=>'required',
            'address'=>'required',
            'weight'=>'required',
            'phonenumber'=>'required',
          ]);
         $merchant=Discount::where('maID',$request->merchantId)->where('delivery_id',$request->daytype)->first();
         $merchantcod=Merchant::where('id',$request->merchantId)->value('cod');
          $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
         $hub=Nearestzone::where('id',$request->reciveZone)->first();
         $intialdcharge = Deliverycharge::find($request->daytype);
         $initialcodcharge = Codcharge::where('status',1)->orderBy('id','DESC')->first();
         // fixed delivery charge
         if($request->weight > 1){
          $extraweight = $request->weight-1;
          $deliverycharge = (int)(($intialdcharge->deliverycharge*1)+(int)($extraweight*$intialdcharge->extradeliverycharge))-$dis;
           $weight = $request->weight;
         }else{
          $deliverycharge = (int)($intialdcharge->deliverycharge-$dis);
          $weight = $request->weight;
         }
    
         // fixed cod charge
         if($request->cod > 100){
        //   $extracod=$request->cod -100;
        //   $extracodcharge = $extracod/100;
        //   $codcharge = $initialcodcharge->codcharge+$extracodcharge;
             $codcharge = 0;
         }else{
        //   $codcharge= $initialcodcharge->codcharge;
            $codcharge = 0;
         }
         
         $update_parcel = Parcel::find($request->hidden_id);
         $update_parcel->invoiceNo = $request->invoiceno;
         if(!$update_parcel->agentId){
         $update_parcel->agentId = $hub->hub_id;
         }
          $update_parcel->user = $update_parcel->user.','.Auth::user()->name;;
         $update_parcel->merchantId = $request->merchantId;
         $update_parcel->reciveZone = $request->reciveZone;
         $update_parcel->cod = $request->cod;
         $update_parcel->percelType = $request->percelType;
         $update_parcel->recipientName = $request->name;
         $update_parcel->recipientAddress = $request->address;
         $update_parcel->pickuploaction = $request->pickuploaction;
         $update_parcel->recipientPhone = $request->phonenumber;
         $update_parcel->productWeight = $weight;
         $update_parcel->note = $request->note;
         $update_parcel->updated_time=date('Y-m-d H:i:s');
         $update_parcel->deliveryCharge = $deliverycharge;
         $update_parcel->codCharge = ($request->cod*$merchantcod)/100;
         $update_parcel->merchantAmount = (int)($request->cod)-($deliverycharge);
         $update_parcel->merchantDue = (int)($request->cod)-($deliverycharge);
         $update_parcel->orderType = $intialdcharge->id;
         $update_parcel->save();
         Toastr::success('Success!', 'Thanks! your parcel update successfully');
         return redirect()->back();
  }
  public function import(Request $request){
    //   dd($request->all());
   
    $b=Excel::import(new AdminParcelimport,request()->file('excel'));
    if($b){
      Toastr::success('Wow! Bulk uploaded', 'success!');
      return redirect()->back();
    }else{
         Toastr::error('Wow! something is wrong', 'success!');
      return redirect()->back();
      
    }
    
    
  }
  public function statusimport(Request $request){
    //   return 1;
      Excel::import(new StatusParcelimport, request()->file('status'));
      Toastr::success('Status Update Success !');
      return redirect()->back();
      
  }
//   for softdelete

  public function deleteParcel(Request $request){
    // return response()->json([
    //         'success' =>$request->hidden_id,
    //     ], 200);
   $parcel= Parcel::where('id',$request->hidden_id)->delete();
    // Toastr::success('Duplicate Parcel Successfully Deleted', 'success!');
    // return redirect()->back();
     return response()->json([
            'success' => 1,
        ], 200);
  }
    
}
