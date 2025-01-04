<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Deliveryman;
use App\Models\CommissionHistory;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\Parcelnote;
use App\Models\Transaction;
use App\Models\PickDrop;
use App\Models\Parceltype;
use App\Exports\RiderParcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use DB;
use Mail;
use Log;
class DeliverymanController extends Controller
{
    public function loginform(){
        return view('frontEnd.layouts.pages.deliveryman.login');
    }
    public function login(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);
       $checkAuth = Deliveryman::where('email',$request->email)
       ->first();
        if($checkAuth){
          if($checkAuth->status == 0){
             Toastr::warning('warning', 'Opps! your account has been suspends');
             return redirect()->back();
         }else{
          if(password_verify($request->password,$checkAuth->password)){
              $deliverymanId = $checkAuth->id;
              $jobstatus = $checkAuth->jobstatus;
               Session::put('deliverymanId',$deliverymanId);
               Session::put('jobstatus',$jobstatus);
               Toastr::success('success', 'Thanks , You are login successfully');
              return redirect('deliveryman/dashboard');
            
          }else{
              Toastr::error('Opps!', 'Sorry! your password wrong');
              return redirect()->back();
          }

           }
        }else{
          Toastr::error('Opps!', 'Opps! you have no account');
          return redirect()->back();
        } 
    }
    
     public function loginWithOtp(Request $request)
    {
        $merchantChedk = Deliveryman::where('phone', $request->mobile)
            ->first();
//  dd($merchantChedk);
        Session::put('deliveryManName', $request->mobile);
        if ($merchantChedk) {
            if ($merchantChedk->status == 0) {
                Toastr::warning('warning', 'Opps! your account has been review');
                return redirect()->back();
            } else {
                if ($request->otp) {
                    $merchantId = $merchantChedk->id;
                    $jobstatus = $merchantChedk->jobstatus;
                    
                    Session::put('deliverymanId', $merchantId);
                    Session::put('jobstatus',$jobstatus);
                    Toastr::success('success', 'Thanks , You are login successfully');
                    return redirect('/deliveryman/dashboard');

                } else {
                    Toastr::error('Opps!', 'Sorry! your OTP is wrong');
                    return redirect()->back();
                }

            }
        } else {
            Toastr::error('Opps!', 'Opps! you have no account');
            return redirect()->back();
        }
    }

    public function sendOtp(Request $request)
    {
// return 1;
        $otp = rand(1000, 9999);
        Log::info("otp = " . $otp);
        $user = Deliveryman::where('phone', '=', $request->mobile)->update(['otp' => $otp]);

        $url = "http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php";
        $number = "$request->mobile";
        $text = "Your Verification OTP code is $otp ";

        $data = array(
            'masking' => "Flingex",
            'userName' => "Flingex",
            'password' => "b57d05174707d151a9369e79af41a5c5",
            'receiver' => "$number",
            'message' => "$text",
        );

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        $p = explode("/", $smsresult);
        $sendstatus = $p[0];
        return response()->json([$user], 200);
    }

   
    
    public function dashboard(){
         $currnetDate = date("Y-m-d");
        //  
      $totalparcel=Parcel::where(['deliverymanId'=>Session::get('deliverymanId')])->where('archive',1)->count();
          $totaldelivery=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>4])->where('archive',1)->count();
          $totalPanding=Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->where('archive',1)->count();
          $totalPicked=Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('archive',1)->count();
          $totalhold=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>5])->where('archive',1)->count();
         $totalintransit=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>3])->where('archive',1)->count();
          $returnpendin=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>6])->count();
        //   dd($returnpendin);
           $returntohub=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>7])->count();
          //$returnmerchant=Parcel::where(['deliverymanId'=>Session::get('deliverymanId'),'status'=>8])->count();
          $Collected =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('archive',1)->where('present_date',$currnetDate)->sum('cod');
           $transactionAmount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->where('status',4)->sum('cod');
          return view('frontEnd.layouts.pages.deliveryman.dashboard',compact('totalparcel','totaldelivery','totalhold','totalPicked','Collected','returntohub','transactionAmount','totalPanding','returnpendin','totalintransit'));
    }
    
     public function transaction(){
    $transaction=Transaction::where('deliveryman_id',Session::get('deliverymanId'))->orderBy('id','DESC')->get();
    $Collected =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->sum('cod');
    $Payamount =Transaction::where('deliveryman_id',Session::get('deliverymanId'))->sum('amount');

    $t=DB::table('parcels')->where('parcels.deliverymanId',Session::get('deliverymanId'))->where('parcels.status',4)->select(\DB::raw('SUM(parcels.cod) as collectedamount,parcels.present_date as date'))
    ->orderBy('parcels.present_date', 'DESC')
    ->groupBy(DB::raw('DATE(parcels.present_date)'))
    ->paginate(15);
    
    return view('frontEnd.layouts.pages.deliveryman.transaction')->with('t',$t)->with('transaction',$transaction)->with('Payamount',$Payamount)->with('Collected',$Collected);
  }
  public function transactions(Request $request){
    $user=Deliveryman::where('id',Session::get('deliverymanId'))->first();
    $tran=new Transaction;
    $tran->deliveryman_id=Session::get('deliverymanId');
    $tran->amount=$request->amount;
    $tran->user=$user->name;
    $tran->status='Accepted';
    $tran->type='agent';
    $tran->save();

  Toastr::success('message', 'Collected Amount has been send successfully!');
      return redirect()->back();
  }
    public function report(Request $request){
    if ($request->startDate) {
    
      
      $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    
      $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    
      $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
    // dd($parcelprice);
      $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
    
      $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
    
      $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
    
      $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

      $parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
      ->where('parcels.deliverymanId',Session::get('deliverymanId'))->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
      ->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();

    }
    else{
      $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('archive',1)->count();
      $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->where('archive',1)->count();
      $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('archive',1)->count();
      $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->where('archive',1)->count();
    
      $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('archive',1)->count();
      $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->where('archive',1)->count();
      $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('archive',1)->count();
      $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('archive',1)->count();
      $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('archive',1)->count();
    
      $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('archive',1)->sum('cod');
    // dd($parcelprice);
      $deliveryCharge =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->sum('deliveryCharge');
    
      $codCharge= Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->sum('codCharge');
    
      $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('archive',1)->sum('cod');
    
      $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->count();

    $parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')->where('parcels.deliverymanId',Session::get('deliverymanId'))
            ->orderBy('parcels.id','DESC')
            ->where('parcels.archive',1)
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
    }
    // return 1;
    return view('frontEnd.layouts.pages.deliveryman.report')->with('parcels',$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount);
  }
     public function parcels(Request $request){
        //  return 1;
         
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
        //   return $request->trackId;
        $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->where('trackingCode',$request->trackId)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('trackingCode',$request->trackId)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
       
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        
        
        ->where('parcels.trackingCode',$request->trackId)
        ->where('parcels.archive',1)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL){
        $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        
        ->where('parcels.archive',1)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        
        ->where('parcels.archive',1)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        
        ->where('parcels.archive',1)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
       }else{
        $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('archive',1)->count();
        $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->where('archive',1)->count();
        $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('archive',1)->count();
        $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->where('archive',1)->count();
        $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',Session::get('deliverymanId'))
        
        ->where('parcels.archive',1)
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('id','DESC')
        ->get()->take(100);
       }
       return view('frontEnd.layouts.pages.deliveryman.parcels',compact('allparcel','parcelr','parcelc','parcelre','parcelpa','parcelpictd','parcelinterjit','parcelhold','parcelrrtupa','parcelrrhub','parcelpriceCOD','deliveryCharge','codCharge','Collectedamount','parcelcount'));
  }
  public function parcel(Request $request){
    // dd($request);
      $aparceltypes = Parceltype::where('slug',$request->slug)->first();
   $filter = $request->filter_id;
   if($request->trackId!=NULL){
     $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelc =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',9)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelpa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',1)->where('trackingCode',$request->trackId)->where('archive',1)->count();
  
    $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelinterjit =Parcel::where('status',3)->where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('trackingCode',$request->trackId)->where('archive',1)->count();
  
    $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
  // dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('deliveryCharge');
  
    $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('codCharge');
  
    $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
  
    $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $allparcel = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    // ->orWhere('parcels.pickupman_id',Session::get('deliverymanId'))
    ->where('parcels.trackingCode',$request->trackId)
    ->where('parcel.archive',1)
    ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    ->orderBy('id','DESC')
    ->get();
   }elseif($request->phoneNumber!=NULL){
    $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelc =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))
   ->where('status',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelpa =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))
   ->where('status',1)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
  
    $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
  
    $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
  // dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('deliveryCharge');
  
    $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('codCharge');
  
    $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
  
    $parcelcount =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('trackingCode',$request->trackId)->where('archive',1)->count();
    $allparcel = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    
    ->where('parcels.archive',1)
    ->where('parcels.recipientPhone',$request->phoneNumber)
    ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    ->orderBy('id','DESC')
    ->get();
   }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('status',9)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',8)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('status',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
  
    $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',2)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',3)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',5)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',6)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',7)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
  
    $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status','!=',9)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
  // dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
  
    $codCharge= $parcelprice =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
  
    $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('status',4)->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
  
    $parcelcount =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $allparcel = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    ->where('parcels.status',$aparceltypes->id)
    ->where('parcels.archive',1)
    ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
    ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    ->orderBy('id','DESC')
    ->get();
   }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
    $allparcel = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    ->where('parcels.recipientPhone',$request->phoneNumber)
    ->where('parcels.archive',1)
    ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
    ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
    ->orderBy('id','DESC')
    ->get();
   }else{
    $parcelr =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',4)->count();
    $parcelc =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',9)->count();
    $parcelre =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',8)->count();
    $parcelpa =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',1)->count();
  
    $parcelpictd =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',2)->count();
    $parcelinterjit =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',3)->count();
    $parcelhold =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',5)->count();
    $parcelrrtupa =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',6)->count();
    $parcelrrhub =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status',7)->count();
  
    $parcelpriceCOD =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('status','!=',9)->sum('cod');
  // dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->sum('deliveryCharge');
  
    $codCharge= $parcelprice =Parcel::where('parcels.deliverymanId',Session::get('deliverymanId'))
    ->where('parcels.status',$aparceltypes->id)->where('archive',1)->sum('codCharge');
  
    $Collectedamount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->sum('cod');
  
    $parcelcount =Parcel::where('deliverymanId',Session::get('deliverymanId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->count();
    
    $allparcel = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    ->where('parcels.status',$aparceltypes->id)
    ->where('parcels.archive',1)
    ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
    ->orderBy('id','DESC')
    ->get();
   }
   $aparceltypes = Parceltype::limit(8)->get();
  return view('frontEnd.layouts.pages.deliveryman.parcels',compact('allparcel','aparceltypes','parcelr','parcelc','parcelre','parcelpa','parcelpictd','parcelinterjit','parcelhold','parcelrrtupa','parcelrrhub','parcelpriceCOD','deliveryCharge','codCharge','Collectedamount','parcelcount'));
}
   public function invoice($id){
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.deliverymanId',Session::get('deliverymanId'))
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.id',$id)
    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
    ->first();
        if($show_data!=NULL){
        	return view('frontEnd.layouts.pages.deliveryman.invoice',compact('show_data'));
        }else{
          Toastr::error('Opps!', 'Your process wrong');
          return redirect()->back();
        }
    }
    
    
    
    public function partial_pay(Request $request){
        $user=Deliveryman::where('id',Session::get('deliverymanId'))->first();
         $parcel = Parcel::where('id',$request->id)->first();
      
    //   $nu1=9999999+$all+11;
      $store_parcel = new Parcel;
     $store_parcel->invoiceNo = $parcel->invoiceNo;
     $store_parcel->user = $user->name;
     $store_parcel->agentId = $parcel->agentId;
     $store_parcel->merchantId = $parcel->merchantId;
     $store_parcel->percelType = $parcel->percelType;
     $store_parcel->reciveZone = $parcel->reciveZone;
     $store_parcel->deliverymanId = $parcel->deliverymanId;
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
    //  $store_parcel->created_time=$parcel->created_time;
     $store_parcel->created_time=date('Y-m-d H:i:s');
     $store_parcel->present_date =date("Y-m-d");
     $store_parcel->merchantAmount = $parcel->cod-$request->partial_pay;
     $store_parcel->merchantDue = $parcel->cod-$request->partial_pay;
     $store_parcel->orderType = $parcel->orderType;
     $store_parcel->codType = $parcel->codType;
     $store_parcel->status = 6;
     $store_parcel->save();
            $note = new Parcelnote();
            $note->parcelId = $store_parcel->id;
            $note->mid=$store_parcel->merchantId;
            $note->note ='Total Cod Price '.$parcel->cod. 'tk. Partial Price '.$request->partial_pay.' TK.';
            $note->parcelStatus = 'Return Pending';
            $note->user=$user->name;
            $note->save();
      
     
        $parcel->cod=$request->partial_pay;
        $parcel->user=$parcel->user.', '.$user->name;
        $parcel->trackingCode  = $parcel->trackingCode.'-P';
        $parcel->status = 4;
        $parcel->merchantAmount = $request->partial_pay-$parcel->deliveryCharge;
        $parcel->merchantDue = $request->partial_pay-$parcel->deliveryCharge;
    	$parcel->save(); 
    	
    	    $note1 = new Parcelnote();
            $note1->parcelId = $parcel->id;  
            $note1->mid=$parcel->merchantId;
            $note1->note = 'Partial Parcel Delivered successfully' ;
            $note1->parcelStatus = 'Partial';
            $note1->user=$user->name;
            $note1->save();
    	
    	Toastr::success('message', 'Partial Pay Add successfully!');
    	return redirect()->back();

    }
    
    // Qrcode reader  
    public function qrcode(Request $request){
        return view('frontEnd.layouts.pages.deliveryman.qrcode');
    }
    
  public function statusupdate(Request $request){
    $user=Deliveryman::where('id',Session::get('deliverymanId'))->first();
      $this->validate($request,[
        'status'=>'required',
      ]); 
       $parcel = Parcel::find($request->hidden_id);
        $parceltype=Parceltype::where('id',$request->status)->first();
            $parcel->status = $request->status;
            $parcel->present_date =date("Y-m-d");
           	$parcel->updated_time =date('Y-m-d H:i:s');
            $parcel->save();
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;            
            $note->note = $request->note;
            $note->mid = $parcel->merchantId;
            $note->user=$user->name;
             $note->parcelStatus = $parceltype->title;
            $note->save();
        if($request->status==3){
        //   $parcel = Parcel::find($request->hidden_id);
        //   $parcel->present_date =date("Y-m-d");
        //   $parcel->save();
        //   if($request->note){
        //          $note = new Parcelnote();
        //          $note->parcelId = $request->hidden_id;            
        //          $note->note = $request->note;
        //          $note->user=$user->name;
        //          $note->save();
        //      }
             

            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
        }  elseif($request->status==7){
            $parcel->hubaprove=0;
            $parcel->save();
        }
        elseif($request->status==4){
             
             $commissionHistory=CommissionHistory::where('deliveryman_id',Session::get('deliverymanId'))->first();
             $parcel->delivered_at=date("Y-m-d");
        	$parcel->update_by=	$parcel->update_by.',D-'.$user->name;
        	$parcel->save();
          $currnetDate = date("Y-m-d");

          $deliveryManId =  Session::get('deliverymanId');
          $parcelCount = 1;
          if($user->commission!=NULL){
               $commissionAmount = $user->commission;
          }
          else{
             $commissionAmount=0; 
          }
         
          $commissionHistoryStatus = 1;

          if(!empty($commissionHistory->deliveryman_id)){
            if($commissionHistory->present_date == $currnetDate){
              $commissionHistory->parchel_count = $commissionHistory->parchel_count+$parcelCount;
              $commissionHistory->commission_amount = $commissionHistory->commission_amount+$commissionAmount;
              $commissionHistory->status = $commissionHistoryStatus;
               $commissionHistory->present_date = $currnetDate;
              $commissionHistory->save();
            }
            $user->commission_amount = $user->commission_amount+$commissionAmount;
            $user->save();
          }
          else{

            if(!empty($deliveryManId)){
              $commissionHistory = new CommissionHistory();
              $commissionHistory->deliveryman_id = $deliveryManId;
              $commissionHistory->parchel_count = $commissionHistory->parchel_count+$parcelCount;
            $commissionHistory->commission_amount = $commissionHistory->commission_amount+$commissionAmount;
            $commissionHistory->status = $commissionHistoryStatus;
             $commissionHistory->present_date = $currnetDate;
              $commissionHistory->save();

              //Delivery Man Commission

              $user->commission_amount = $user->commission_amount+$commissionAmount;
              $user->save();
          }

          }
            
        //   $parcel = Parcel::find($request->hidden_id);
        //     $parcel->status = $request->status;
        //     $parcel->save();
            
            // if($request->note){
            //     $note = new Parcelnote();
            //     $note->parcelId = $request->hidden_id;
            //     $note->note = 'Parcel delivered successfully';
            //     $note->user=$user->name;
            //     $note->save();
            // }
            
            $merchantinfo =Merchant::find($parcel->merchantId);
            
             $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'trackingCode' => $parcel->trackingCode,
            );
             $send = Mail::send('frontEnd.emails.perceldeliverd', $data, function($textmsg) use ($data){
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Deliverd Notification');
            });
        }
      Toastr::success('message', 'Parcel information update successfully!');
      return redirect()->back();
    }
    
    public function commissionHistory(Request $request){

        if($request->startDate){
            // $showCommissionHistory = DB::table('dm_commission_history')
            // ->where('dm_commission_history.deliveryman_id',Session::get('deliverymanId'))
            // ->whereBetween('dm_commission_history.present_date', [$request->startDate, $request->endDate])
            // ->join('deliverymen', 'dm_commission_history.deliveryman_id', '=', 'deliverymen.id' )
            // ->select('dm_commission_history.*', 'deliverymen.name')
            //   ->orderBy('id','DESC')
            // ->get();
              $showCommissionHistory =  CommissionHistory::where('deliveryman_id',Session::get('deliverymanId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->orderBy('id','DESC')->get();
              
             
      }
      else{
         $showCommissionHistory = CommissionHistory::where('deliveryman_id',Session::get('deliverymanId'))->orderBy('id','DESC')->get(); 
      }
      
    //   dd($showCommissionHistory);
      
      return view('frontEnd.layouts.pages.deliveryman.commission_history',compact('showCommissionHistory'));

    }
    public function pickup(){
      $show_data = DB::table('pickups')
      ->where('pickups.deliveryman',Session::get('deliverymanId'))
      ->orderBy('pickups.id','DESC')
      ->select('pickups.*')
      ->get();
      $deliverymen = Deliveryman::where('status',1)->get();
      return view('frontEnd.layouts.pages.deliveryman.pickup',compact('show_data','deliverymen'));
    }
    public function pickupdeliverman(Request $request){
        $this->validate($request,[
          'deliveryman'=>'required',
        ]);
        $pickup = Pickup::find($request->hidden_id);
        $pickup->deliveryman = $request->deliveryman;
        $pickup->save();

        Toastr::success('message', 'A deliveryman asign successfully!');
        return redirect()->back();
        $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
        $agentInfo =Agent::find($parcel->merchantId);
        $data = array(
         'contact_mail' => $agentInfo->email,
         'ridername' => $deliverymanInfo->name,
         'riderphone' => $deliverymanInfo->phone,
         'codprice' => $pickup->cod,
        );
        $send = Mail::send('frontEnd.emails.percelassign', $data, function($textmsg) use ($data){
         $textmsg->from('info@flingex.com');
         $textmsg->to($data['contact_mail']);
         $textmsg->subject('Pickup Assign Notification');
        });
          
    }
    public function pickupstatus(Request $request){
      $this->validate($request,[
        'status'=>'required',
      ]);
      $pickup = Pickup::find($request->hidden_id);
      $pickup->status = $request->status;
      $pickup->save();
    
      Toastr::success('message', 'Pickup status update successfully!');
      return redirect()->back();
    }
    public function passreset(){
      return view('frontEnd.layouts.pages.deliveryman.passreset');
    }
    public function passfromreset(Request $request){
      $this->validate($request,[
            'email' => 'required',
        ]);
        $validDeliveryman =Deliveryman::Where('email',$request->email)
       ->first();
        if($validDeliveryman){
             $verifyToken=rand(111111,999999);
             $validDeliveryman->passwordReset  = $verifyToken;
             $validDeliveryman->save();
             Session::put('resetDeliverymanId',$validDeliveryman->id);
             
             $data = array(
             'contact_mail' => $validDeliveryman->email,
             'verifyToken' => $verifyToken,
            );
            $send = Mail::send('frontEnd.layouts.pages.deliveryman.forgetemail', $data, function($textmsg) use ($data){
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Forget password token');
            });
          return redirect('deliveryman/resetpassword/verify');
        }else{
              Toastr::error('Sorry! You have no account', 'warning!');
             return redirect()->back();
        }
    }
    public function saveResetPassword(Request $request){
      // return "okey";
       $validDeliveryman = Deliveryman::find(Session::get('resetDeliverymanId'));
        if($validDeliveryman->passwordReset==$request->verifyPin){
           $validDeliveryman->password   = bcrypt(request('newPassword'));
           $validDeliveryman->passwordReset  = NULL;
             $validDeliveryman->save();
             
             Session::forget('resetDeliverymanId');
             Session::put('deliverymanId',$validDeliveryman->id);
             Toastr::success('Wow! Your password reset successfully', 'success!');
             return redirect('deliveryman/dashboard');
        }else{
          return $request->verifyPin;
            Toastr::error('Sorry! Your process something wrong', 'warning!');
             return redirect()->back();
        }
       
    }
    public function resetpasswordverify(){
        if(Session::get('resetDeliverymanId')){
        return view('frontEnd.layouts.pages.deliveryman.passwordresetverify');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect('forget/password');
        }
    }
    public function logout(){
        Session::flush();
        Toastr::success('Success!', 'Thanks! you are logout successfully');
        return redirect('deliveryman/logout');
    }
     public function export( Request $request ) {
        return Excel::download( new RiderParcelExport(), 'parcel.xlsx') ;
    
    }
    
     public function accept(Request $request){
      // dd($id);
      $parcel= Parcel::where('id',$request->hidden_id)->first();
      // dd($parcel);
      if($request->dmanaprove==1){
        $parcel->dmanaprove=$request->dmanaprove;
        $parcel->save();
        $note = new Parcelnote();
        $note->parcelId = $request->hidden_id;            
        $note->note = "Delivery Man Accepted this Parcel";
        $note->user=Session::get('deliverymanId');
        $note->save();
      }else{
        $parcel->dmanaprove=$request->dmanaprove;
        $parcel->save();
        $note = new Parcelnote();
        $note->parcelId = $request->hidden_id;            
        $note->note = "Delivery Man Rejected this Parcel";
        $note->user=Session::get('deliverymanId');
        $note->save();
      }
      
      Toastr::success('message', 'Parcel has been accepted successfully!');
        return redirect()->back();
  
  
    }
    
     public function pickupaccept(Request $request){
      // dd($id);
      $parcel= Parcel::where('id',$request->hidden_id)->first();
      // dd($parcel);
      if($request->pmanaprove==1){
        $parcel->pmanaprove=$request->pmanaprove;
        $parcel->status=2;
        $parcel->save();
        $note = new Parcelnote();
        $note->parcelId = $request->hidden_id; 
        $note->mid = $parcel->merchantId;
        $note->note = "Pickup Man Accepted this Parcel";
        $note->user=Session::get('deliverymanId');
        $note->save();
      }else{
        $parcel->pmanaprove=$request->pmanaprove;
        $parcel->save();
        $note = new Parcelnote();
        $note->parcelId = $request->hidden_id;            
        $note->note = "Pickup Man Rejected this Parcel";
        $note->user=Session::get('deliverymanId');
        $note->save();
      }
      
      Toastr::success('message', 'Parcel has been accepted successfully!');
        return redirect()->back();
  
  
    }
    
    public function profileUpdate(Request $request){
      
        	$this->validate($request,[
    		'name'=>'required',
    	 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    	]);
    	   
    	 $deliveryman_info = Deliveryman::find($request->hidden_id);
    	 
        
        // image upload
    	$update_file = $request->file('image');
    // 	dd($update_file);exit;
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/deliveryman/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
    	}else{
    	     $oldFileUrl = ('public/uploads/deliveryman/').$deliveryman_info->image;
                if (file_exists($oldFileUrl)) {
                    unlink($oldFileUrl);
                }
    		$fileUrl = $deliveryman_info->image;
    	}
       
        $deliveryman_info->name  		= 	$request->name;
        if(!empty($request->password)){
              $deliveryman_info->password 		= 	bcrypt(request('password'));
        }
    	$deliveryman_info->image 		= 	$fileUrl;
    	$deliveryman_info->save();
    	
    	Toastr::success('message', 'My Profile Updated successfully!');
    	return redirect('deliveryman/dashboard');
    }
    
}
