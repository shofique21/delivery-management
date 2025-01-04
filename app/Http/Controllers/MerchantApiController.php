<?php
namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Merchant;
use App\Models\Nearestzone;
use App\Models\Deliverycharge;
use App\Models\Codcharge;
use App\Models\Parcel;
use App\Imports\ParcelImport;
use App\Exports\ParcelExport;
use App\Models\Employee;
use App\Models\Price;
use App\Models\Pickup;
use App\Models\Discount;
use App\Models\Merchantpayment;
use App\Models\Parcelnote;
use App\Models\Parceltype;
use App\Models\Deliveryman;
use App\Models\Agent;
use App\Models\About;
use App\Models\Counter;
use App\Models\PickDrop;
use App\Models\Issue;
use App\Models\IssueDetail;
use App\Models\Complain;
use App\Models\SecretWithdrawal;
use App\Models\Merchant_notification;
use Session;
use DB;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MerchantApiController extends Controller
{
    
   
    
    public function register(Request $request){
    // 	  $validator= $this->validate($request,[
    //     //   'companyName'=>'required',
    //         'phoneNumber'=>'required|unique:merchants',
    //         'emailAddress'=>'required|unique:merchants',
    //         'username'=>'required|unique:merchants',
    //         'password'=>'required|same:confirmed',
    //         'confirmed'=>'required',
    //     //   'agree'=>'required',
    //       ]);
          $validator = Validator::make($request->all(), [
             'companyName'=>'required',
            'phoneNumber'=>'required|unique:merchants',
            'emailAddress'=>'required|unique:merchants',
            'username'=>'required|unique:merchants',
            'password'=>'required|same:confirmed',
            'confirmed'=>'required',
        ]);
         if ($validator->fails()){
            return response()->json(["status" => "Fails","message" => "Validation Fails","error" => $validator->errors()],422);
        }
          
            $store_data				   =    new Merchant();
            $store_data->companyName   =   $request->companyName;
            $store_data->firstName     =   $request->firstName;
    	    $store_data->phoneNumber   =   $request->phoneNumber;
            $store_data->emailAddress  =   $request->emailAddress;
            $store_data->username      =   $request->username;
    	    $store_data->pickLocation  =   $request->pickLocation;
            $store_data->paymentMethod =   $request->paymentMethod;
           
            $store_data->status        =    1;
            $store_data->verify        =    1;
            $store_data->agree         =    $request->agree;
            $store_data->password 	   =	bcrypt(request('password'));
            $token = \Str::random(60);
            $store_data->token = $token;
            $store_data->save();
      
        return response()->json(["status" => "success", "msg" => "Account Creaated Successfully!",  "code" => 200, "token" => $token]);
        
    
    }
    // withdrawal_request
    public function withdrawal_request(Request $request){
        // return response()->json(['msg'=>$request->parcel_id]) ;
        $parcels_id = $request->parcel_id;
        // if($request->parcel_id){
           
            //  dd($parcel); 
        
        
           foreach($parcels_id as $id){
                $parcel =Parcel::where('id',$id)->first();
                // return response()->json(['msg'=>$parcel]) ;
                $parcel->withdrawal=1;
                $parcel->save();
                
                 
        // }
             
       }
    //   $parcel->save();
       return response()->json(["status" => "success", "msg" => "Thanks! your Withdrawal request send  successfully",  "code" => 200, ]);
       
        }
    // pickup request 
    public function pickuprequest(Request $request){
     $this->validate($request,[
        'pickupAddress'=>'required',
      ]);
      $time= date('H:i', time());
      
      $date = date('Y-m-d');
      $findpickup = Pickup::where('date',$date)->where('status',0)->Where('merchantId',Session::get('merchantId'))->count();
         if($findpickup){
             return response()->json(["status" => "wrong", "msg"=>"Opps!', 'Sorry! your pickup request already pending", "code" => 200]);
         }else{
             $store_pickup = new Pickup;
             $store_pickup->merchantId = Session::get('merchantId');
             $store_pickup->pickuptype = 0;
             $store_pickup->area  = $request->area;
             $store_pickup->pickupAddress = $request->pickupAddress;
             $store_pickup->note = $request->note;
             $store_pickup->phone = $request->phone;
             $store_pickup->date = $date;
              $store_pickup->time = $time;
            //   $store_pickup->status = 7;
             $store_pickup->estimedparcel = $request->estimedparcel;
             $store_pickup->save();
             return response()->json(["status" => "success","msg"=>"Thanks! your pickup request send  successfully", "code" => 200]);
             
         }
     
  }
    public function login(Request $request){
        // return 1;
        $this->validate($request,[
            // 'username' => 'required',
            'password' => 'required',
        ]);
       $merchantChedk =Merchant::where('username',$request->username)
       ->first();
        Session::put('merchantName',$request->username);
        if($merchantChedk){
          if($merchantChedk->status == 0 || $merchantChedk->verify == 0){
              return response()->json(["status" => "error", "msg" => "Opps! your account has been review", "code" => 404]);
            //  Toastr::warning('warning', 'Opps! your account has been review');
            //  return redirect()->back();
         }else{
          if(password_verify($request->password,$merchantChedk->password)){
            if($merchantChedk->token==null)
            {
            $token = \Str::random(60);
            $merchantChedk->token = $token;
            $merchantChedk->save();
            }else{
                $token = $merchantChedk->token;
            }

            return response()->json(["status" => "success", "msg" => "Your are logged in successfully!", "code" => 200, "token" => $token]);
            
          }else{
            //   Toastr::error('Opps!', 'Sorry! your password wrong');
              return response()->json(["status" => "error", "msg" => "The Password is wrong!", "code" => 401]);
          }

           }
        }else{
        //   Toastr::error('Opps!', 'Opps! you have no account');
          return response()->json(["status" => "error", "msg" => "No Account was found!", "code" => 404]);
        } 
    }
    
    public function complain(){
      $allComplain = DB::table('complains')
      ->leftJoin('issue_details', 'issue_details.id','=','complains.issue_id')
      ->leftJoin('issues', 'issues.id','=','complains.type_issue_id')
      ->where('complains.merchantId',Session::get('merchantId'))
       ->select('complains.*','issue_details.details as issue','issues.name as issuetype')
      ->orderBy('id','DESC')
      ->get();
      // dd($allComplain);
      $issues = Issue::all();
         return response()->json(["status" => "success", compact('issues','allComplain'), "code" => 200]);
    }
    
    public function notifications(){
         $announcements=Merchant_notification::where('status',1)->get();
    	 return response()->json(["status" => "success", compact('announcements'), "code" => 200]);
    }
    
     public function getIssueDetalisByIssue($issue_id)
    {
        $issuedetail = DB::table('issue_details')->where('status',1)->where('issue_id', $issue_id)->get();
        //dd($issuedetail);
        return response()->json(["status" => "success", compact('issuedetail'), "code" => 200]);
    }

    public function addComplain(Request $request){
      // 'subject','type_issue','issue', 'details','status',
      // dd($request->all());
      $newComplain = new Complain;
      $newComplain->merchantId = Session::get('merchantId');
      $newComplain->subject = $request->subject;
      $newComplain->type_issue_id = $request->type_issue;
      $newComplain->issue_id = $request->issue;
      $newComplain->details = $request->details;
      $newComplain->status = 1;
      $newComplain->save();
      
      return response()->json(["status" => "Thanks!! Your Complain Submit successfully",  "code" => 200]);

    }

     // status 
    public function status(){
        $parcelstatus=Parceltype::get();
        return response()->json(["status" => "success", "code" => 200
            , "data" => compact('parcelstatus')
          ]);
    }
    
// push notofication
public function push(Request $request){

define('API_ACCESS_KEY','AAAA9ItiJik:APA91bHUAAhp_dZzbOs1kmLNMD8HzwshQX39S1ZkQpAk6p2CG2WoPeRgg9dy8IYlGUU9OB0HYU0a5mnA5f-3X9hWfOicijaUy_HOgDCWuZoRng9qfmALn2lJ5NKDgrkDVx6SYr5xpVm5');

$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

$token = "f7W2gJeGRai9hYEBg_BzTV:APA91bEY_cxpR4GaF0tu6B4WiTbN3pMlnpUtJwnWarCsm-cq04wjNfdgXTyVrth_iRFhdqXaXNw5StO8iD1e7rsmysbTd0LPOYUOHOy30fjqbzwNplHMMgZQUe_v_8lRWx1pGRkTBcQn";


$notification =[
   "body"=> 'notificatiobody', "title"=>'title',
   

];

$extraNotificationData = ["message" => 'test',"moredata" =>'tes2'];

$fcmNotification = [
//'registration_ids' => $tokenList, //multple token array
'to'        =>$token, //single token
'notification' => $notification,
'data' => $extraNotificationData
];

$headers = [
'Authorization: key=' . API_ACCESS_KEY,
'Content-Type: application/json'
];


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$fcmUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
$result = curl_exec($ch);
curl_close($ch);

return response()->json(["status" => $result]);
// dd($result) ;



        
    }
    public function push_get(Request $request){
        $pushcontact=Counter::where('status',1)->get();
        return response()->json(["status" => 1, "code" => 200,
        "data" => compact('pushcontact'),
          ]);
        
    }
    // withdrawal request
    public function withdrawal(Request $request){
        $withdrawal=new SecretWithdrawal;
        $withdrawal->merchant_id=Session::get('merchantId');
        $withdrawal->amount=$request->Withdrawal;
        $withdrawal->mstatus=1;
        $withdrawal->mWithdrawal=1;
        $withdrawal->save();
        return response()->json(["status" => "success", "msg" => "Thanks! your Withdrawal request send  successfully", "code" => 200]);
        
        
    }
    public function withdrawalget(){
        $withdrawal= SecretWithdrawal::where('merchant_id',Session::get('merchantId'))->where('mstatus',1)->select('mWithdrawal','amount','created_at')->orderBy('id','DESC')->get();
        return response()->json(["status" => "success", "code" => 200
            , "data" => compact('withdrawal')
          ]);
    }
    // Merchant Login Function End
 // forget password
    public function passfromreset(Request $request){
      $this->validate($request,[
            'phoneNumber' => 'required',
        ]);
        $validMerchant = Merchant::Where('phoneNumber',$request->phoneNumber)
       ->first();
        if($validMerchant){
        //  Session::put('resetCustomerId',$validMerchant->id);
         return response()->json(["status" => "success", "msg" => "FORGET PASSWORD RESET FORGET PASSWORD!",  "code" => 200, "mid"=>$validMerchant->id]);
        }else{
            return response()->json(["status" => "warning", "msg" => "Sorry! You have no account",  "code" => 200,]);
              
        }
        }
        
    // archive
    public function archive(){
        $allarchiveparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.archive',2)
        ->where('parcels.merchantId',Session::get('merchantId'))
         ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
        
         return response()->json(["status" => "success", "code" => 200
            , "data" => compact('allarchiveparcel')
          ]);

    }
    
    public function saveResetPassword(Request $request){
      $validMerchant = Merchant::find(request('mid'));
        if($validMerchant){
    	     $validMerchant->password 	=	bcrypt(request('newPassword'));
    	     $validMerchant->passwordReset 	=	NULL;
    	     $token = \Str::random(60);
    	     $validMerchant->token = $token;
             $validMerchant->save();
             
            //  Session::forget('resetCustomerId');
            //  Session::put('merchantId',$validMerchant->id);
             return response()->json(["status" => "success", "msg" => "Wow! Your password reset successfully",  "code" => 200, "token" => $token]);
             
        }else{
            return response()->json(["status" => "warning", "msg" => "Sorry! Your process something wrong",  "code" => Session::get('resetCustomerId'),]);
           
        }
       
    }
    public function dashboard(){
      $id = Session::get("id");
          $totalpercel=Parcel::where(['merchantId'=>$id])->where('archive',1)->count();
          $totalArchivePercel=Parcel::where(['merchantId'=>$id])->where('archive',2)->count();
          $pendingparcel=Parcel::where(['merchantId'=>$id,'status'=>1])->where('archive',1)->count();
          $pickedparcel=Parcel::where(['merchantId'=>$id,'status'=>2])->where('archive',1)->count();
          $InTransitparcel=Parcel::where(['merchantId'=>$id,'status'=>3])->where('archive',1)->count();
          $deliverd=Parcel::where(['merchantId'=>$id,'status'=>4])->where('archive',1)->count();
          $ReturnedToHub=Parcel::where(['merchantId'=>$id,'status'=>7])->where('archive',1)->count();
          $ReturnPending=Parcel::where(['merchantId'=>$id,'status'=>6])->where('archive',1)->count();
          $cancelparcel=Parcel::where(['merchantId'=>$id,'status'=>9])->where('archive',1)->count();
          $parcelreturn=Parcel::where(['merchantId'=>$id,'status'=>8])->where('archive',1)->count();
          $totalhold=Parcel::where(['merchantId'=>$id,'status'=>5])->where('archive',1)->count();
          $totalamount=Parcel::where(['merchantId'=>$id,'status'=>4])->where('archive',1)->sum('cod');
          $availabeAmount=(Parcel::where(['merchantId'=>$id,'status'=>4])->where('merchantpayStatus',null)->where('archive',1)->where('present_date','!=',date('Y-m-d'))->sum('cod'))-(Parcel::where('merchantId', Session::get('merchantId'))->where('merchantpayStatus',null)->where('status',4)->where('archive',1)->where('present_date','!=',date('Y-m-d'))->sum('deliveryCharge'));
          $merchantUnPaid=Parcel::where('merchantId',$id)->where('status',4)->whereNull('merchantpayStatus')->where('archive',1)->sum('merchantAmount');
          $withdrawalParcel=Parcel::where('merchantId',Session::get('merchantId'))->where('merchantpayStatus',null)->where('present_date','!=',date('Y-m-d'))->where('withdrawal',0)->where('status',4)->where('archive',1)->get();
          $merchantPaid=Parcel::where(['merchantId'=>$id,'merchantpayStatus'=>1])->where('archive',1)->sum('merchantAmount');
          $merchantAmount=Parcel::where('merchantId', $id)->where('status','!=',9)->where('archive',1)->sum('merchantAmount');
          $deliveryCharge=Parcel::where('merchantId', Session::get('merchantId'))->where('status',4)->where('archive',1)->sum('deliveryCharge');
          $WithdrawalBlance= Parcel::where(['merchantId'=>Session::get('merchantId'),'status'=>4])->where('present_date','!=',date('Y-m-d'))->where('archive',1)->sum('cod');
          $totalWithdrawalBlance= SecretWithdrawal::where(['merchant_id'=>Session::get('merchantId'),'mstatus'=>1])->sum('amount');

          return response()->json(["status" => "success", "code" => 200
            , "data" => compact('availabeAmount','deliveryCharge','withdrawalParcel','totalArchivePercel','totalpercel','WithdrawalBlance','totalWithdrawalBlance','pickedparcel','InTransitparcel','ReturnPending','ReturnedToHub','merchantAmount','pendingparcel','deliverd','parcelreturn','cancelparcel','totalhold','totalamount','merchantUnPaid','merchantPaid')
          ]);

    }
    // Merchant Dashboard
    public function profile(){
        $profileinfos = Merchant::where('id',Session::get('merchantId'))->first();
      return response()->json(["status" => "success", "msg" => "Profile details here", "code" => 200, "data" => compact('profileinfos')]);
      
    }
     public function area_id(){
        $area = Nearestzone::where('status',1)->select('id','zonename')->orderBy('id','DESC')->get();
        return response()->json(["status" => "success", "msg" => "Zone details here", "code" => 200, "data" => compact('area')]);
    }
    public function zone(){
        $nearestzones = Nearestzone::where('status',1)->get();
        return response()->json(["status" => "success", "msg" => "Zone details here", "code" => 200, "data" => compact('nearestzones')]);
    }
    public function profileEdit(){
        $merchantInfo = Merchant::find(Session::get('merchantId'));
        $nearestzones = Nearestzone::where('status',1)->get(["id", "zonename"]);
        return response()->json(["status" => "success", "msg" => "Merchant profile details here", "code" => 200, "data" => compact('merchantInfo', 'nearestzones')]);      
    }
    public function support(){
        return view('frontEnd.layouts.pages.merchant.support');
    }
    // support 
    public function merchantsupport(Request $request){
    //   $this->validate($request, [
    //      'subject'=>'required',
    //      'description'=>'required',
    //     ]);
      $findMerchant = Merchant::find(Session::get('merchantId'));
      $data = array(
         'contact_email' => $findMerchant->emailAddress,
         'description' => $request->description,
        );
        
        $send = Mail::send('frontEnd.emails.support',$data, function($textmsg) use ($data){
         $textmsg->from($data['contact_email']);
         $textmsg->to('info@flingex.com');
         $textmsg->subject($data['description']);
        });
        return response()->json(["status" => "success", "msg" => "Message sent successfully!", "code" => 200, ]);
        
    }
    // Merchant Profile Edit
        public function profileUpdate(Request $request){
        $update_merchant = Merchant::find(Session::get('merchantId'));
        $update_file = $request->file('image');
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/merchant/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
	    	$update_merchant->logo=$fileUrl;
    	}
        if($request->phoneNumber){
        $update_merchant->phoneNumber = $request->phoneNumber;
        $update_merchant->firstName = $request->name;
        $update_merchant->emailAddress = $request->emailAddress;
        $update_merchant->companyName = $request->companyName;
        }
        if($request->pickLocation){
        $update_merchant->pickLocation = $request->pickLocation;
        $update_merchant->nearestZone = $request->nearestZone;
        $update_merchant->pickupPreference = $request->pickupPreference;
        }
        if($request->paymentMethod){
        $update_merchant->paymentMethod = $request->paymentMethod;
        $update_merchant->withdrawal = $request->withdrawal;
        }
        if($request->nameOfBank){
        $update_merchant->nameOfBank = $request->nameOfBank;
        $update_merchant->bankBranch = $request->bankBranch;
        $update_merchant->bankAcHolder = $request->bankAcHolder;
        $update_merchant->bankAcNo = $request->bankAcNo;
        }
        if($request->bkashNumber){
        $update_merchant->bkashNumber = $request->bkashNumber;
        $update_merchant->roketNumber = $request->roketNumber;
        $update_merchant->nogodNumber = $request->nogodNumber;
        }
        $update_merchant->save();

        return response()->json(["status" => "success", "msg" => "Merchant profile updated successfully!", "code" => 200]);      
    }
    // Merchant Profile Update
    public function logout(Request $request){
        // return 1;
        Session::flush();
        return response()->json(["status" => "success", "msg" => "Thanks! you are logout successfully", "code" => 200]);
    }
    // Merchant Logout
    public function deliverycharge(){
      $deliverycharge = Deliverycharge::where('status',1)->select('id','title','deliverycharge')->get();
      return response()->json(["msg" => "success", "code" => 200, "data" => compact('deliverycharge')]);
    }
 
    public function chooseservice(){
      $pricing = Deliverycharge::where('status',1)->get();
      $pickdateline=About::where('status',1)->select('pickupDateline')->first();
      return response()->json(["msg" => "success", "code" => 200, "data" => compact('pricing','pickdateline')]);
    }
    public function parcelcreate($slug){
      $ordertype = Deliverycharge::where('slug', $slug)->first();
      $areas = Nearestzone::where('status',1)->get();
      $codcharge = Codcharge::where('status',1)->orderBy('id','DESC')->first();
      Session::forget('codpay');
      Session::forget('pcodecharge');
      Session::forget('pdeliverycharge');
      if($ordertype){
        return response()->json(["status" => "success", "msg" => "choose a parcel to create a new parcel", "code" => 200, "data" => compact('ordertype','codcharge','areas')]);
      }
    }
  //Parcel Oparation
  public function parcelstore(Request $request){
     $this->validate($request,[
        'cod'=>'required',
        'percelType'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
      ]);
      
       $hub=Nearestzone::where('id',$request->reciveZone)->first();
       $merchant=Discount::where('maID',Session::get('merchantId'))->where('delivery_id',Session::get('ordertype'))->first();
       $merchantcod=Merchant::where('id',Session::get('merchantId'))->value('cod');
       
       $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
     // fixed delivery charge
     if($request->weight > 1 || $request->weight !=NULL){
      $extraweight = $request->weight-1;
      $deliverycharge = ((Session::get('deliverycharge')*1)+($extraweight*Session::get('extradeliverycharge')))-$dis;
      $weight = $request->weight;
     }else{
      $deliverycharge = (Session::get('deliverycharge')-$dis);
      $weight = 1;
     }
     // fixed cod charge
     if($request->cod > 100){
    //   $extracod=$request->cod -100;
    //   $extracodcharge = $extracod/100;
      $extracodcharge = 0;
      $codcharge = Session::get('codcharge')+$extracodcharge;
     }else{
      $codcharge= Session::get('codcharge');
     }
     $all=Parcel::all()->count();     
	 $random = \Str::random(3);
     $nu1=$random.rand(3,8055);
     $store_parcel = new Parcel;
     $store_parcel->invoiceNo = $request->invoiceNo;
     $store_parcel->merchantId = Session::get('merchantId');
     $store_parcel->user = Session::get('merchantName');
     $store_parcel->cod = $request->cod;
     $store_parcel->percelType = $request->percelType;
     $store_parcel->agentId = $hub->hub_id;
     $store_parcel->recipientName = $request->name;
     $store_parcel->recipientAddress = $request->address;
     $store_parcel->recipientPhone = $request->phonenumber;
     $store_parcel->pickuploaction=$request->pickuploaction;
     $store_parcel->productWeight = $weight;
     $store_parcel->trackingCode  = 'MA'.$nu1;
     $store_parcel->note = $request->note;
     $store_parcel->deliveryCharge = $deliverycharge;
     $store_parcel->created_time=date('Y-m-d H:i:s');
     $store_parcel->codCharge = ($request->cod*$merchantcod)/100;
     $store_parcel->reciveZone = $request->reciveZone;
     $store_parcel->productPrice = $request->productPrice;
     $store_parcel->merchantAmount = ($request->cod)-($deliverycharge);
     $store_parcel->merchantDue = ($request->cod)-($deliverycharge);
     $store_parcel->orderType = Session::get('ordertype');
     $store_parcel->codType = Session::get('codtype');
     $store_parcel->status = 1;
     $store_parcel->save();
     
     $note = new Parcelnote();
     $note->parcelId = $store_parcel->id;
     $note->note = 'parcel create successfully';
     $note->save();
     
     $data = array(
         'trackingCode' =>  $store_parcel->trackingCode,
         'subject' => 'New Parcel Place',
        );
         // return $data;
         $send = Mail::send('frontEnd.emails.parcelplace', $data, function($textmsg) use ($data){
         $textmsg->to('info@flingex.com');
         $textmsg->subject($data['subject']);
        });
     
     return response()->json(["status" => "success", "msg" => "Thanks! your parcel add successfully", "percel_id"=>$store_parcel->id, "tracking_id"=>$store_parcel->trackingCode, "invoice_id"=>$store_parcel->invoiceNo, "code" => 2002]);
  } 
  public function parcelstoreApi(Request $request){
    //   return 1;
    //   return response()->json(["status" => $request]);
        // dd($request);
      $this->validate($request,[
        'cod'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
        'weight'=>'required|numeric|min:1|max:20',
      ]);
      
      $merchant=Discount::where('maID',Session::get('merchantId'))->where('delivery_id',$request->daytype)->first();
      $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
      $hub=Nearestzone::where('id',$request->reciveZone)->first();
     
     // fixed delivery charge
     $intialdcharge = Deliverycharge::find($request->choose_service_type_id);
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
    $random = \Str::random(4);
    $nu1=$random.rand(2,50);
     $store_parcel = new Parcel;
     $store_parcel->invoiceNo = $request->invoiceNo;
      $store_parcel->user = Session::get('merchantName');
     $store_parcel->agentId = $hub->hub_id;
     $store_parcel->merchantId = Session::get('merchantId');
     $store_parcel->percelType = $request->percelType;
     $store_parcel->reciveZone = $request->reciveZone;
     $store_parcel->cod = $request->cod;
     $store_parcel->recipientName = $request->name;
     $store_parcel->recipientAddress = $request->address;
     $store_parcel->recipientPhone = $request->phonenumber;
     $store_parcel->productWeight = $weight;
     $store_parcel->trackingCode  = 'MA'.$nu1;
     $store_parcel->note = $request->note;
     $store_parcel->deliveryCharge = $deliverycharge;
     $store_parcel->codCharge = $codcharge;
     $store_parcel->created_time=date('Y-m-d H:i:s');
     $store_parcel->merchantAmount = (int)($request->cod)-($deliverycharge);
     $store_parcel->merchantDue = (int)($request->cod)-($deliverycharge);
     $store_parcel->orderType = $intialdcharge->id;
     $store_parcel->codType = $initialcodcharge->id;
     $store_parcel->status = 1;
    //  return $store_parcel;
     $store_parcel->save();
     
            $note = new Parcelnote();
            $note->parcelId = $store_parcel->id;            
            $note->note = 'Parcel Create successfully ';
            $note->parcelStatus = 'Pending';
            $note->user=Session::get('merchantName');
            $note->save();
     return response()->json(["status" => "success", "msg" => "Thanks! your parcel add successfully", "percel_id"=>$store_parcel->id, "tracking_id"=>$store_parcel->trackingCode, "invoice_id"=>$store_parcel->invoiceNo, "code" => 200]);
 
     
    //  Toastr::success('Success!', 'Thanks! your parcel add successfully');
    //  return redirect('editor/parcel/create');
  } 
   public function merchant_parcel_create(Request $request){
    //   return 1;
    //   return response()->json(["status" => $request]);
        // dd($request);
      $this->validate($request,[
        'cod'=>'required',
        'customer_name'=>'required',
        'customer_address'=>'required',
        'customer_phonenumber'=>'required',
        // 'weight'=>'required|numeric|min:1|max:20',
      ]);
      
      $merchant=Discount::where('maID',$request->merchantId)->where('delivery_id',$request->delivery_charge_id)->first();
      $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
      $hub=Nearestzone::where('id',$request->area_id)->first();
     
     // fixed delivery charge
     $intialdcharge = Deliverycharge::find($request->delivery_charge_id);
     $initialcodcharge = Codcharge::where('status',1)->orderBy('id','DESC')->first();
     if(1 > 1 || 1 !=NULL){
      $extraweight = 1-1;
       $deliverycharge = (int)(($intialdcharge->deliverycharge*1)+($extraweight*$intialdcharge->extradeliverycharge))-$dis;
       $weight = 1;
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
    // $all=Parcel::all()->count();
    $random = \Str::random(4);
    $nu1=$random.rand(2,50);
     $store_parcel = new Parcel;
     $store_parcel->invoiceNo = $request->invoiceNo;
      $store_parcel->user = "OhSoGo";
     $store_parcel->agentId = $hub->hub_id;
     $store_parcel->merchantId = $request->merchantId;
     $store_parcel->percelType = 1;
     $store_parcel->reciveZone = $request->area_id;
     $store_parcel->cod = $request->cod;
     $store_parcel->recipientName = $request->customer_name;
     $store_parcel->recipientAddress = $request->customer_address;
     $store_parcel->recipientPhone = $request->customer_phonenumber;
     $store_parcel->productWeight = $weight;
     $store_parcel->trackingCode  = 'MA'.$nu1;
     $store_parcel->note = $request->note;
     $store_parcel->deliveryCharge = $deliverycharge;
     $store_parcel->codCharge = $codcharge;
     $store_parcel->created_time=date('Y-m-d H:i:s');
     $store_parcel->merchantAmount = (int)($request->cod)-($deliverycharge);
     $store_parcel->merchantDue = (int)($request->cod)-($deliverycharge);
     $store_parcel->orderType = $intialdcharge->id;
     $store_parcel->codType = $initialcodcharge->id;
     $store_parcel->status = 1;
    //  return $store_parcel;
     $store_parcel->save();
     
            $note = new Parcelnote();
            $note->parcelId = $store_parcel->id;            
            $note->note = 'Parcel Create successfully ';
            $note->parcelStatus = 'Pending';
            $note->user="OhSoGo";
            $note->save();
     return response()->json(["status" => "success", "msg" => "Thanks! your parcel add successfully", "percel_id"=>$store_parcel->id, "tracking_id"=>$store_parcel->trackingCode, "invoice_id"=>$store_parcel->invoiceNo, "code" => 200]);
 
     
    //  Toastr::success('Success!', 'Thanks! your parcel add successfully');
    //  return redirect('editor/parcel/create');
  } 
public function cancel(Request $request){
  
  $parcel =Parcel::where('id',$request->pid)->first();
  $parcel->user = Session::get('merchantName');
  $parcel->status=9;
  $parcel->save();

  Toastr::error('Opps!', 'Your Parcel has been cancel');
         return redirect()->back();
  // dd($ordertype);
 

}

   public function pickupmanage(){
      $show_data = DB::table('pickups')
      ->where('pickups.merchantId',Session::get('merchantId'))
      ->leftJoin('deliverymen','deliverymen.id','=','pickups.deliveryman')
      ->orderBy('pickups.id','DESC')
      ->select('pickups.*','deliverymen.name')
      ->get();
      return response()->json(["status" => "success", "msg" => "Data loaded successfully!", "code" => 200, "data" => compact('show_data')]);
    }

// api get percels
      public function parcel(Request $request){
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
           $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('trackingCode',$request->trackId)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('trackingCode',$request->trackId)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('trackingCode',$request->trackId)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('trackingCode',$request->trackId)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('trackingCode',$request->trackId)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('trackingCode',$request->trackId)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('trackingCode',$request->trackId)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('trackingCode',$request->trackId)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('trackingCode',$request->trackId)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','parceltypes.title','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL){
    $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('recipientPhone',$request->phoneNumber)->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('recipientPhone',$request->phoneNumber)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('recipientPhone',$request->phoneNumber)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','parceltypes.title','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }
       elseif($request->orderNumber!=NULL){
           $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('invoiceNo',$request->orderNumber)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('invoiceNo',$request->orderNumber)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('invoiceNo',$request->orderNumber)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('invoiceNo',$request->orderNumber)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('invoiceNo',$request->orderNumber)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('invoiceNo',$request->orderNumber)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('invoiceNo',$request->orderNumber)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('invoiceNo',$request->orderNumber)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('invoiceNo',$request->orderNumber)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('invoiceNo',$request->orderNumber)->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('invoiceNo',$request->orderNumber)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('invoiceNo',$request->orderNumber)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.invoiceNo',$request->orderNumber)
        ->select('parcels.*','parceltypes.title','merchants.firstName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }
       
       elseif($request->startDate!=NULL && $request->endDate!=NULL){
    $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','parceltypes.title','merchants.firstName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->trackId!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
           $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','parceltypes.title','merchants.firstName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }else{
    $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('archive',1)
         ->select('parcels.*','parceltypes.title','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }
        
       return response()->json(["status" => "success", "code" => 200
       , "data" => compact('allparcel','parcelr','parcelc','parcelre','parcelpa','parcelpictd','parcelinterjit','parcelhold','parcelrrtupa','parcelrrhub','parcelprice','parcelamount','parcelcount')
      ]);
  }
    // lafz api start
    public function lafz(Request $request){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->whereIn('parcels.merchantId',[24, 230])
         ->select('parcels.*','parceltypes.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
        return response()->json(["status" => "success", "code" => 200
       , "data" => compact('allparcel')
      ]);
    }
  // lafz api end
  public function parcels(Request $request){
  
      $aparceltypes = Parceltype::where('slug',$request->slug)->first();
    
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
            $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('trackingCode',$request->trackId)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('trackingCode',$request->trackId)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('trackingCode',$request->trackId)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('trackingCode',$request->trackId)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('trackingCode',$request->trackId)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('trackingCode',$request->trackId)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('trackingCode',$request->trackId)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('trackingCode',$request->trackId)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('trackingCode',$request->trackId)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->count();
      
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','merchants.firstName','merchants.lastName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL){
           $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('recipientPhone',$request->phoneNumber)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('recipientPhone',$request->phoneNumber)->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('recipientPhone',$request->phoneNumber)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('recipientPhone',$request->phoneNumber)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }
       elseif($request->startDate!=NULL && $request->endDate!=NULL){
            $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->trackId!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('status','!=',9)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',Session::get('merchantId'))
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }else{
    $parcelr =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',4)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',9)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',8)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',1)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',2)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',3)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',5)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',6)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->where('status',7)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->where('status',$aparceltypes->id)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
        ->where('parcels.merchantId',Session::get('merchantId'))->where('parcels.status',$aparceltypes->id)
        ->where('archive',1)
         ->select('parcels.*','merchants.firstName','merchants.lastName','deliverycharges.title as parcelType','deliverycharges.time as Eta','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
      
       }
    return response()->json(["status" => "success", "code" => 200
       , "data" => compact('allparcel','parcelr','parcelc','parcelre','parcelpa','parcelpictd','parcelinterjit','parcelhold','parcelrrtupa','parcelrrhub','parcelprice','parcelamount','parcelcount')
      ]);
        
  }
  
  public function report(Request $request){
   $to=$request->startDate;
      $end=$request->endDate;
     $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',Session::get('merchantId'))->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    $parceldeliverd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('merchantId',Session::get('merchantId'))->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('merchantId',Session::get('merchantId'))->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpictd =Parcel::where('merchantId',Session::get('merchantId'))->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('merchantId',Session::get('merchantId'))->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('merchantId',Session::get('merchantId'))->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('merchantId',Session::get('merchantId'))->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('merchantId',Session::get('merchantId'))->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelprice =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('cod');
    $parcelamount =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantPaid');
    $parcelcount =Parcel::where('merchantId',Session::get('merchantId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    // return view('frontEnd.layouts.pages.merchant.report')->with($parcelr)->with($parcelcount)->with('',$parcelc)->with($parcelprice)->with($parcelamount)->with($parcelpa)->with($parcelpictd)->with($parcelinterjit)->with($parcelhold)->with($parcelhold)->with($parcelrrtupa)->with($parcelre)->with($parcelrrhub);

    return response()->json(["status" => "success", "msg" => "All Reports!", "code" => 200, "data" => compact('show_data','parcelc', 'parcelprice', 'parcelamount', 'parceldeliverd', 'parcelcount', 'parcelprice', 'parcelpa', 'parcelpictd', 'parcelinterjit', 'parcelhold', 'parcelhold', 'parcelrrtupa', 'parcelre', 'parcelrrhub')]);

  }
  public function parceldetails($id){
    $parceldetails= DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where(['parcels.merchantId'=>Session::get('merchantId'),'parcels.id'=>$id])
        ->select('parcels.*','nearestzones.zonename')
        ->first();
      $trackInfos = Parcelnote::where('parcelId',$id)->orderBy('id','ASC')->get();
      return response()->json(["status" => "success", "msg" => "Parcel details here!", "code" => 200, "data" => compact('parceldetails','trackInfos')]);

    //   return view('frontEnd.layouts.pages.merchant.parceldetails',);
  }
  public function parcelnotification(){
    $parcelnotification= DB::table('parcelnotes')
        ->join('parcels', 'parcelnotes.parcelId','=','parcels.id')
         ->where(['parcelnotes.mid'=>Session::get('merchantId')])
         ->orderBy('id', 'DESC')
         ->where('parcelnotes.created_at',date('Y-m-d'))
        ->select('parcelnotes.*','parcels.trackingCode')
        ->get();
      
      return response()->json(["status" => "success", "msg" => "Parcel Notification here!", "code" => 200, "data" => compact('parcelnotification')]);

    //   return view('frontEnd.layouts.pages.merchant.parceldetails',);
  }
  public function allparcelnotification(){
    $allparcelnotification= DB::table('parcelnotes')
        ->join('parcels', 'parcelnotes.parcelId','=','parcels.id')
         ->where(['parcelnotes.mid'=>Session::get('merchantId')])
        ->select('parcelnotes.*','parcels.trackingCode')
        ->get();
      
      return response()->json(["status" => "success", "msg" => "Parcel All Notification here!", "code" => 200, "data" => compact('allparcelnotification')]);

    //   return view('frontEnd.layouts.pages.merchant.parceldetails',);
  }
   public function invoice($id){
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where(['parcels.merchantId'=>Session::get('merchantId'),'parcels.id'=>$id])
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.id',$id)
    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
    ->first();
        if($show_data!=NULL){
          return response()->json(["status" => "success", "msg" => "Parcel details here!", "code" => 200, "data" => compact('show_data')]);

        }else{
          return response()->json(["status" => "error", "msg" => "no parcel data found!", "code" => 404]);
        }
    }
  public function parceledit($id){
      $parceledit=Parcel::where(['merchantId'=>Session::get('merchantId'),'id'=>$id])->first();
      if($parceledit !=NULL){
      $ordertype = Deliverycharge::find($parceledit->orderType);
      $codcharge = Codcharge::find($parceledit->codType);
      $areas = Nearestzone::where('status',1)->get();
      Session::put('codpay',$parceledit->cod);
      Session::put('pcodecharge',$parceledit->codCharge);
      Session::put('pdeliverycharge',$parceledit->deliveryCharge);
      return view('frontEnd.layouts.pages.merchant.parceledit',compact('ordertype','codcharge','parceledit','areas'));
      }else{
         Toastr::error('Opps!', 'Your process wrong');
         return redirect()->back();
      }
  }
  
public function parcelupdate(Request $request){
     $this->validate($request,[
        'cod'=>'required',
        'name'=>'required',
        'address'=>'required',
        'phonenumber'=>'required',
      ]);
      $merchant=Discount::where('maID',Session::get('merchantId'))->where('delivery_id',Session::get('ordertype'))->first();
      $dis=0;
        if($merchant){
           $dis+= $merchant->discount;
        }
         // fixed delivery charge
        if($request->weight > 1 || $request->weight !=NULL){
          $extraweight = $request->weight-1;
          $deliverycharge = ((Session::get('deliverycharge')*1)+($extraweight*Session::get('extradeliverycharge')))-$dis;
          $weight = $request->weight;
         }else{
          $deliverycharge = (Session::get('deliverycharge')-$dis);
          $weight = 1;
         }

         // fixed cod charge
         if($request->cod > 100){
        //   $extracod=$request->cod -100;
        //   $extracodcharge = $extracod/100;
        //   $codcharge = Session::get('codcharge')+$extracodcharge;
        $codcharge = 0;
         }else{
             $codcharge = 0;
           Session::get('codcharge');
         }
         $update_parcel = Parcel::find($request->hidden_id);
         $update_parcel->invoiceNo = $request->invoiceno;
         $update_parcel->merchantId = Session::get('merchantId');
         $update_parcel->cod = $request->cod;
         $update_parcel->percelType = $request->percelType;
         $update_parcel->recipientName = $request->name;
         $update_parcel->recipientAddress = $request->address;
         $update_parcel->recipientPhone = $request->phonenumber;
         $update_parcel->productWeight = $weight;
         $update_parcel->note = $request->note;
         $update_parcel->reciveZone = $request->reciveZone;
         $update_parcel->deliveryCharge = $deliverycharge;
         $update_parcel->updated_time=date('Y-m-d H:i:s');
         $update_parcel->codCharge = $codcharge;
         $update_parcel->orderType = Session::get('ordertype');
         $update_parcel->codType = Session::get('codtype');
         $update_parcel->save();
         Toastr::success('Success!', 'Thanks! your parcel update successfully');
         return redirect()->back();
  }
  public function singleservice(Request $request){
      
      $data = array(
              'contact_mail' => 'info@flingex.com',
              'address' => $request->address,
              'area' => $request->area,
              'note' => $request->note,
              'estimate' => $request->estimate,
            );
            $send = Mail::send('frontEnd.emails.singleservice', $data, function($textmsg) use ($data){
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('A Single Service Request');
            });
            
            $pickdroup= new PickDrop;
        //   dd($pickdroup);
            $pickdroup->address=$request->address;
            $pickdroup->area=$request->area;
            $pickdroup->note=$request->note;
            $pickdroup->estimate=$request->estimate;
            $pickdroup->phone=$request->phone;
            $pickdroup->save(); 
            // return 5;
            
        Toastr::success('Success!', 'Thanks! your  request send successfully');
        return redirect()->back();
  }
  public function payments(){
      
    //  $merchantInvoice= Merchantpayment::where('merchantId',Session::get('merchantId'))
    //  ->select(\DB::raw('SUM(parcels.cod) as price,count(parcels.paymentInvoice) as pid'))
    //  ->join('parcels', 'merchantpayments.merchantId', '=', 'parcels.merchantId')
    //  ->groupBy('merchantpayments.parcelId')
    //  ->get();

      $merchantInvoice= DB::table('merchantpayments')
      ->where('merchantpayments.merchantId',Session::get('merchantId'))
        ->leftJoin('parcels', 'merchantpayments.id','=','parcels.paymentInvoice')
         ->select(\DB::raw('SUM(parcels.cod) as totalCod,count(parcels.paymentInvoice) as totalInvoice,merchantpayments.created_at,merchantpayments.id'))
         ->groupBy('parcels.paymentInvoice')
         ->orderBy('merchantpayments.id','DESC')
         ->get();
    //   $merchantInvoice = Merchantpayment::where('merchantId',Session::get('merchantId'))->get();
      return response()->json(["status" => "success", "msg" => "All Paymets Details!", "code" => 200, "data" => compact('merchantInvoice')]);

  }
  public function inovicedetails($id){
        $invoiceInfo = Merchantpayment::find($id)->where("merchantId", Session::get("merchantId"))->first();
        $inovicedetails = Parcel::where('paymentInvoice',$id)->where("merchantId", Session::get("merchantId"))->get();
        return response()->json(["status" => "success", "msg" => "Paymet details here!", "code" => 200, "data" => compact('invoiceInfo','inovicedetails')]);
    }
    
    public function parceltrack($trackid){
        
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
         ->leftJoin('deliverycharges', 'parcels.orderType', '=', 'deliverycharges.id')
         ->where('parcels.trackingCode',$trackid)
         ->orWhere('parcels.recipientPhone',$trackid)
         ->where('parcels.merchantId',Session::get('merchantId'))
         ->select('parcels.*','nearestzones.zonename','deliverymen.name as dname','deliverymen.phone as dphone','deliverycharges.title as parcelType','deliverycharges.time as Eta')
         ->orderBy('id','DESC')
         ->first();
      
        if($trackparcel){
            $trackInfos = Parcelnote::where('parcelId',@$trackparcel->id)->orderBy('id','ASC')->get();
            return response()->json(["status" => "success", "msg" => "Parcel detaisl here!", "code" => 200, "data" => compact('trackparcel','trackInfos')]);      
      }else{
          
            return response()->json(["status" => "failed", "msg" => "Parcel  failed!", "code" => 200, ]);
        }
        
    }
    public function parceltrackfont($trackid){
        
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id') 
         ->where('parcels.trackingCode',$trackid)
         ->orWhere('parcels.recipientPhone',$trackid)
         ->select('parcels.*','nearestzones.zonename','deliverymen.name as dname','deliverymen.phone as dphone')
         ->orderBy('id','DESC')
         ->first();
      
        if($trackparcel){
            $trackInfos = Parcelnote::where('parcelId',@$trackparcel->id)->orderBy('id','ASC')->get();
            return response()->json(["status" => "success", "msg" => "Parcel detaisl here!", "code" => 200, "data" => compact('trackparcel','trackInfos')]);      
      }else{
          
            return response()->json(["status" => "failed", "msg" => "Parcel  failed!", "code" => 200, ]);
        }
        
    }
// otp login
   public function loginWithOtp(Request $request){
      
      $merchantChedk =Merchant::where('phoneNumber',$request->mobile)->where('otp',$request->otp)
       ->first();
       
        Session::put('merchantName',$request->mobile);
        if($merchantChedk){
          if($merchantChedk->status == 0 || $merchantChedk->verify == 0){
             return response()->json(["status" => "error", "msg" => "Opps! your account has been review", "code" => 401]);
         }else{
          if($request->otp){
              $merchantId = $merchantChedk->id;
               Session::put('merchantId',$merchantId);
                $token = \Str::random(60);
                $merchantChedk->token = $token;
                $merchantChedk->save();
              return response()->json(["status" => "success", "msg" => "Your are logged in successfully!", "code" => 200, "token" => $token]);
            
          }else{
              return response()->json(["status" => "error", "msg" => "The Otp is wrong!", "code" => 401]);
          }

           }
        }else{
          return response()->json(["status" => "error", "msg" => "Your  Account not found!", "code" => 404]);
        } 
  }
  
   public function sendOtp(Request $request){
// return 1;
    $otp = rand(1000,9999);
    // Log::info("otp = ".$otp);
    $user = Merchant::where('phoneNumber','=',$request->mobile)->first();

    if($user){
        $user->otp=$otp;
        $user->save();
        // ->update(['otp' => $otp])
        // send otp to mobile no using sms api
       
        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number="$request->mobile";
        $text="Your Verification OTP code is $otp ";
        
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
    
    return response()->json(["status" => "success", "msg" => "Your OTP has been send successfully!", "code" => 200,]);
    }else{
        return response()->json(["status" => "wrong", "msg" => "Your Number not found!", "code" => 400,]);
    }
}
  
}
