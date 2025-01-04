<?php

namespace App\Http\Controllers\editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\Nearestzone;
use App\Models\Deliverycharge;
use App\Models\Discount;
use App\Models\Marchant_offer;
use App\Models\Merchant_notification;
use App\Models\Replycomplain;
use App\Models\Complain;
use DB;
use DataTables;
use Auth;
use App\Models\Post;
use App\Models\Merchantpayment;
use Mail;
use Exception;
class MerchantOperationController extends Controller
{
    public function manage(){
        if (request()->ajax()) {
    	$merchants = Merchant::orderBy('id','DESC');
        return Datatables::of($merchants)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<ul class="action_buttons dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                        data-toggle="dropdown">Action Button
                        <span class="caret"></span></button>
                    
                    </ul>';

                    return $button;
                })
                ->addColumn('action', function ($row) {
                    $button = '<ul class="action_buttons dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                        data-toggle="dropdown">Action Button
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                        
                        <li>
                            <a class="thumbs_up"
                                href="/editor/merchant/edit/'.$row->id.'"
                                title="Edit"><i class="fa fa-edit"></i> Edit</a>
                        </li>
                        <li>
                            <a class="edit_icon"
                                href="/editor/merchant/view/'.$row->id.'"
                                title="View"><i class="fa fa-eye"></i> View</a>
                        </li>
                        <li>
                            <a class="edit_icon"
                                href="/editor/merchant/payment/invoice/'.$row->id.'"
                                title="View"><i class="fa fa-list"></i> Invoice</a>
                        </li>
                        <li>
                            <a href="/editor/merchant/dis/'.$row->id.'"
                                class="btn btn-sm btn-dark">Discount</a>
                        </li>
                        <li>
                             <a class="edit_icon btn btn-sm merchantid" data-toggle="modal" data-target="#mechantType" mid="' . $row->id . '" title="Merchant Type"> Type</a>
                        </li>
                        <li>
                             <a class=" btn btn-sm btn-info merchantCod" data-toggle="modal" data-target="#mechantCod" mid="' . $row->id . '" cod="' . $row->cod . '" title="Merchant Cod"> Cod Charge</a>
                        </li>
                        
                    </ul>
                    </ul>';

                    return $button;
                })
                ->addColumn('status', function($row){
                $type='';
                if($row->merchant_type=='1'){
                   $type.='Prepaid'; 
                }elseif($row->merchant_type=='2')
                {
                    $type.='Postpaid'; 
                }
                
                if($row->status==1){
                $button='<button  style="border:0; background: none; padding: 0 !important" type="button" title="Update Status"  class="btn-sm Approved">Active</button><form action="/editor/merchant/inactive"
                method="POST">
                 <input type="hidden" name="_token" value=" '.csrf_token().' " />
                <input type="hidden" name="hidden_id"
                    value="'.$row->id.'">
                <button type="submit" class="thumbs_up"
                    title="unpublished"><i class="fa fa-thumbs-up"></i>
                    Inactive</button><br> '.$type.' 
            </form>';
            return $button;
        }
                else{
                    $button='<button type="button"  style="border:0; background: none; padding: 0 !important" title="Update Status" class=" btn-sm Notapproved" >Inactive</button><form action="/editor/merchant/active" method="POST"> 
                     
                     <input type="hidden" name="_token" value=" '.csrf_token().' " />
                    <input type="hidden" name="hidden_id"
                        value="'.$row->id.'">
                    <button type="submit" class="thumbs_down"
                        title="published"><i class="fa fa-thumbs-down"></i>
                        Active</button>
                </form>';
                return $button;
                
          }})
                ->rawColumns(['action','status'])
                ->make(true);
        }
    	return view('backEnd.merchant.mv');
 
    // 	$merchants = Merchant::orderBy('updated_at','DESC')->get();
    // 	return view('backEnd.merchant.manage',compact('merchants'));
    }
    // merchantType?
    public function merchantType(Request $request){
        $merchant=Merchant::where('id',$request->hidden_id)->first();
        $merchant->merchant_type=$request->type;
        $merchant->save();
        return response()->json([
                'success' => 1,
            ], 200);
        
    }
    
    // merchantCod?
    public function merchantCod(Request $request){
        $merchant=Merchant::where('id',$request->hidden_id)->first();
        $merchant->cod=$request->ecod;
        $merchant->save();
        return response()->json([
                'success' => 1,
            ], 200);
        
    }
    public function unpaid(){
        $merchants = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantpayStatus',null)
            ->where('parcels.archive',1)
           ->whereIn('parcels.status',[4,8])
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')->groupBy(DB::raw('parcels.merchantId'))
            ->get();
            return view('backEnd.merchant.unpaid',compact('merchants'));
            
    }
    public function merchantrequest(){
    	$merchants = Merchant::where('verify',0)->orderBy('id','DESC')->get();
    	return view('backEnd.merchant.merchantrequest',compact('merchants'));
    }
    public function profileedit($id){
    	$merchantInfo = Merchant::find($id);
    	$nearestzones = Nearestzone::where('status',1)->get();
    	return view('backEnd.merchant.edit',compact('merchantInfo','nearestzones'));
    }
     public function dailyinvoice(Request $request){
         $to=$request->startDate;
         $end=$request->endDate;
        //  dd($request);
        if($request->startDate && $request->endDate){
            $merchantInvoice = Merchantpayment::whereDate('created_at','>=',$request->startDate)->whereDate('created_at','<=',$request->endDate)->orderBy('id','DESC')->get();
        }else{
        $merchantInvoice = Merchantpayment::orderBy('id','DESC')->get()->take(10);
        }
        return view('backEnd.merchant.dailyinvoice',compact('merchantInvoice','to','end'));

    }
      // Merchant Profile Edit
        public function profileUpdate(Request $request){
        // dd($request->cpassword);
        $update_merchant = Merchant::find($request->hidden_id);
        if($request->cpassword){
          $update_merchant->password =bcrypt(request('cpassword'));
        }
        $update_merchant->firstName   = $request->firstName;
       
        $update_merchant->companyName   = $request->companyName;
        $update_merchant->phoneNumber   = $request->phoneNumber;
        $update_merchant->pickLocation  = $request->pickLocation;
        $update_merchant->nearestZone   = $request->nearestZone;
        $update_merchant->pickupPreference = $request->pickupPreference;
        $update_merchant->paymentMethod = $request->paymentMethod;
        $update_merchant->withdrawal    = $request->withdrawal;
        $update_merchant->nameOfBank    = $request->nameOfBank;
        $update_merchant->bankBranch    = $request->bankBranch;
        $update_merchant->bankAcHolder  = $request->bankAcHolder;
        $update_merchant->bankAcNo      = $request->bankAcNo;
        $update_merchant->bkashNumber   = $request->bkashNumber;
        $update_merchant->roketNumber   = $request->roketNumber;
        $update_merchant->nogodNumber   = $request->nogodNumber;
         $update_merchant->discount      = $request->discount;
        $update_merchant->save();
         Toastr::success('message', 'Merchant  info update successfully!');
        return redirect()->back();
    }
     public function inactive(Request $request){
        $inactive_merchant = Merchant::find($request->hidden_id);
        $inactive_merchant->status=0;
        $inactive_merchant->save();
        Toastr::success('message', 'Merchant  inactive successfully!');
        return redirect('/editor/merchant/manage');
    }

    public function active(Request $request){
        $active_merchant = Merchant::find($request->hidden_id);
        $active_merchant->status=1;
        $active_merchant->verify=1;
        $active_merchant->save();
        
        $active_merchant = Merchant::find($request->hidden_id);
        
        $url = "http://66.45.237.70/api.php";
        $number="0$active_merchant->phoneNumber";
        $text="Dear $active_merchant->companyName \r\n  Successfully boarded your account. Now you can login & enjoy our services. If any query call us +880 1977593593  \r\n Regards,\r\n Flingex ";
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
        
         
        Toastr::success('message', 'Merchant active successfully!');
        return redirect()->back();
        
    }
     public function abaileblance($id){
          $merchantInfo = Merchant::find($id);
          $tQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.status','!=',9)
            ->count();
            $tV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.status','!=',9)
            ->sum('parcels.cod');
        $panndingQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[1])
            ->count();
            $panndingV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[1])
            ->sum('parcels.cod');
            
            $deliverdQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4])
            ->count();
            $deliverdV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4])
            ->sum('parcels.cod');
             $rmQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[8])
            ->count();
            $rmV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[8])
            ->sum('parcels.cod');
            
            $deliverychargeQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->count(); 
            
            $deliverychargeV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.deliveryCharge'); 
            
             $undeliverychargeQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[2,3,5,6,7])
            ->count();
            
            $undeliverychargeV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[2,3,5,6,7])
            ->sum('parcels.cod'); 
            
            $payamoutV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.cod'); 
             
             $merchantAmount = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantAmount');
             
             $merchantPaid = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantPaid');

        $merchantDue = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus',null)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantDue');

        $parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)->whereIn('parcels.status',['4','8'])
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus',null)
            ->where('parcels.present_date','!=',date('Y-m-d'))
            // ->where('parcels.withdrawal',0)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
        $availabeAmount=(Parcel::where(['merchantId'=>$id,'status'=>4])->where('merchantpayStatus',null)->where('archive',1)->where('present_date','!=',date('Y-m-d'))->sum('cod'))-(Parcel::where('merchantId', $id)->where('merchantpayStatus',null)->where('status',4)->where('archive',1)->where('present_date','!=',date('Y-m-d'))->sum('deliveryCharge'));
        //  dd($availabeAmount);
    	return view('backEnd.merchant.view',compact('merchantAmount','availabeAmount','merchantDue','merchantInfo','merchantPaid','undeliverychargeQ','payamoutV','undeliverychargeV','parcels','deliverychargeV','deliverychargeQ','rmV','rmQ','deliverdQ','deliverdV','tQ','tV','panndingQ','panndingV'));
    }
    public function view($id){
          $merchantInfo = Merchant::find($id);
          $tQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.status','!=',9)
            ->count();
            $tV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.status','!=',9)
            ->sum('parcels.cod');
        $panndingQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[1])
            ->count();
            $panndingV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[1])
            ->sum('parcels.cod');
            
            $deliverdQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4])
            ->count();
            $deliverdV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4])
            ->sum('parcels.cod');
             $rmQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[8])
            ->count();
            $rmV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[8])
            ->sum('parcels.cod');
            
            $deliverychargeQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->count(); 
            
            $deliverychargeV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.deliveryCharge'); 
            
             $undeliverychargeQ = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[2,3,5,6,7])
            ->count();
            
            $undeliverychargeV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[2,3,5,6,7])
            ->sum('parcels.cod'); 
            
            $payamoutV = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.cod'); 
             
             $merchantAmount = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantAmount');
             
             $merchantPaid = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantPaid');

        $merchantDue = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus',null)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantDue');

        $parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)->whereIn('parcels.status',[4,8])
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus',null)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
        $availabeAmount=(Parcel::where(['merchantId'=>$id,'status'=>4])->where('merchantpayStatus',null)->where('archive',1)->where('present_date','!=',date('Y-m-d'))->sum('cod'))-(Parcel::where('merchantId', $id)->where('merchantpayStatus',null)->where('status',4)->where('archive',1)->where('present_date','!=',date('Y-m-d'))->sum('deliveryCharge'));
         
    	return view('backEnd.merchant.view',compact('merchantAmount','availabeAmount','merchantDue','merchantInfo','merchantPaid','undeliverychargeQ','payamoutV','undeliverychargeV','parcels','deliverychargeV','deliverychargeQ','rmV','rmQ','deliverdQ','deliverdV','tQ','tV','panndingQ','panndingV'));
    }
    
    public function allparcelview($id){
          $merchantInfo = Merchant::find($id);

          $totalamount = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.cod');
             // deliverd /return merchant start
            $marcentamount = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantAmount');
            $merchantDue = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantDue');
           
            $collectedAmount = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4])
            ->sum('parcels.cod'); 
            $merchantPaid = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantPaid'); 
            $deliverycharge = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.deliveryCharge'); 
            $totaldue = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->where('parcels.merchantpayStatus', null)
            ->whereIn('parcels.status',[4,8])
            ->sum('parcels.merchantDue');
            // deliverd /return merchant end
            $parcel = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->whereIn('parcels.status',[4,8])
            ->count();


        $parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantId', $id)
            ->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
    	return view('backEnd.merchant.allview',compact('merchantInfo','deliverycharge','parcel','totalamount','totaldue','parcels','collectedAmount','merchantDue','marcentamount','merchantPaid'));
    }
    public function paymentinvoice($id){
        $merchantInvoice = Merchantpayment::where('merchantId',$id)->orderBy('id','DESC')->get();
        return view('backEnd.merchant.paymentinvoice',compact('merchantInvoice'));
    }
    public function inovicedetails($id){
        $invoiceInfo = Merchantpayment::find($id);
        $inovicedetails = Parcel::where('paymentInvoice',$id)->get();
        $merchantInfo = Merchant::find($invoiceInfo->merchantId);
        return view('backEnd.merchant.inovicedetails',compact('inovicedetails','invoiceInfo','merchantInfo'));
    }
    
     public function discount(Request $request){
        // dd($request);
        // $merchant= Merchant::where('id',$request->id)->first();
        // $merchant->discount=$request->discount;
        // $merchant->save();
        $typename=Deliverycharge::where('id',$request->delivery_id)->first();
        $discount=new Discount;
        $discount->maID=$request->maID;
        $discount->delivery_id=$request->delivery_id;
        $discount->dliveryTypeName=$typename->title;
        $discount->discount=$request->discount;
        $discount->save();

        Toastr::success('message', 'Merchant Discount successfully!');
        return back();

    }
    public function dis($id){
        $delivery=Deliverycharge::get();
        $merchants = Merchant::where('id',$id)->first();
    	return view('backEnd.merchant.discount',compact('merchants','delivery'));
    }
    
     public function manage_offer(){
          $offers=Marchant_offer::get();
    	return view('backEnd.merchant.offer',compact('offers'));
    }
    
    public function create_offer(Request $request){
        // dd($request);
       $this->validate($request,[
            	'name'=>'required',
    		'inside_amount'=>'required|numeric',
    			'outside_amount'=>'required|numeric',
    	]);
    	 $offer_info = Marchant_offer::find($request->hidden_id);
    	 
       if(!empty($offer_info)){
         
        $offer_info->name=$request->name;
        $offer_info->inside_amount=$request->inside_amount;
        $offer_info->outside_amount=$request->outside_amount;
         $offer_info->status=1;
        $offer_info->save();  
       }
       else{
            $offer=new Marchant_offer;
            $offer->name=$request->name;
        $offer->inside_amount=$request->inside_amount;
        $offer->outside_amount=$request->outside_amount;
         $offer->status=1;
        $offer->save();  
       }

        Toastr::success('message', 'Merchant Offer Create successfully!');
        return back();

    }
    
        public function changeStatus(Request $request)

    {
        
          Marchant_offer::where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }
    
     public function offer_delete($id){
        $offer=Marchant_offer::where('id',$id)->delete();
        Toastr::success('message', ' Offer Delete has been successfully!');
        return back();

    }

    public function discount_delete($id){
        $discount=Discount::where('id',$id)->delete();
        Toastr::success('message', ' Discount Delete has been successfully!');
        return back();

    }
   
    public function merchantComplain(Request $request){

        // $allComplain = DB::table('complains')
        // ->leftJoin('issue_details', 'issue_details.id','=','complains.issue_id')
        // ->leftJoin('issues', 'issues.id','=','complains.type_issue_id')
        // // ->where('complains.merchantId',Session::get('merchantId'))
        //  ->select('complains.*','issue_details.details as issue','issues.name as issuetype')
        // ->orderBy('id','DESC')
        // ->paginate(20);

        if (request()->ajax()) {
            $allComplain =  DB::table('complains')
            ->leftJoin('issue_details', 'issue_details.id','=','complains.issue_id')
            ->leftJoin('issues', 'issues.id','=','complains.type_issue_id')
            // ->where('complains.merchantId',Session::get('merchantId'))
             ->select('complains.*','issue_details.details as issue','issues.name as issuetype')
            ->orderBy('id','DESC');
            return Datatables::of($allComplain)
                    ->addIndexColumn()
                    ->addColumn('merchant', function ($row) {
                        $merchant=Merchant::where('id',$row->merchantId)->first();
                        $merchant = '#'.@$merchant->id.'-'.@$merchant->companyName.'('. @$merchant->firstName .'-'.@$merchant->phoneNumber.')';
                        return $merchant;
                        
                        // return ($row->cod-$row->deliveryCharge);
                    })
                    ->addColumn('action', function ($row) {
                        $button = '<button type="button" title="Action" data-toggle="modal" cid="'.$row->id.'" data-target="#complainId" class="status btn btn-info btn-sm">Replay</button>';
    
                        return $button;
                    })
                    ->addColumn('action', function ($row) {
                        $rComplain=Replycomplain::where("complain_id",$row->id)->orderBy("id","DESC")->get();
                        $reply='';
                        foreach($rComplain as $pn){
                            $reply.='
                            <p class="text-right">'.$pn->user_name.'<small>('.\Carbon\Carbon::createFromTimestamp(strtotime($pn->created_at))->format('g:ia d M Y').')'.'</small></p>
                            <div class="p-3 bg-white mb-3 shadow rounded border text-right">
                            <p>'.$pn->details.'</p>
                        </div>
                            ';
                            }
                        $button = '<button type="button" title="Action" data-toggle="modal" cid="'.$row->id.'" data-target="#complainId'.$row->id.'" class="status btn btn-info btn-sm">Replay</button>
                        <div class="modal fade" id="complainId'.$row->id.'">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-3">
                                    Merchant Complain Box 
                                </p>
                                '.$reply.'
                               
                                <div class="p-3 bg-light mb-3 shadow rounded border">
                                    <p>'.$row->details.'</p>
                                </div>
                   
                                    <input type="hidden" name="hidden_id" value="" id="hidden_id">
                
                                    <textarea name="details" id="massage" class="form-control" placeholder="Reply"></textarea>
                                    <br>
                                    
                                    <select class="form-control" id="statuss">
                                    <option value="Processing">Processing</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Solved">Solved</option>
                                    </select>
                                    <br>
                                    <div class="form-group">
                                        <button data-dismiss="modal" id="replyComplin"
                                            class="btn btn-success">Submit Reply</button>
                                    </div>            
                                   
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                        ';
    
                        return $button;
                    })
                    ->addColumn('status', function($row){
                    
                    $button='<h5 class="align-middle" style="color: #f25e0f;
                    ">'.$row->status.'</h5>';
                return $button;
            
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
            }
           

        return view('backEnd.merchant.complain');


    }

    public function replyComplain(Request $request)
    {
        //  dd($request);
        Complain::where(['id' => $request['hidden_id']])->update([
            'status' => $request->status,
        ]);
        $Complain= new Replycomplain();
        $Complain->user_name=Auth::user()->name;
        $Complain->complain_id=$request->hidden_id;
        $Complain->details=$request->details;
         $Complain->status=2;
        $Complain->save();  
        return response()->json([
            'success' => 1,
        ], 200);
    }
    
    public function notifications(){
       $notifications=Merchant_notification::get();
    	return view('backEnd.merchant.notifications',compact('notifications')); 
    }
    
     public function create_nofification(Request $request){
        // dd($request);
       $this->validate($request,[
            	'title'=>'required',
    		'description'=>'required',
    	]);
    	 $notification_info = Merchant_notification::find($request->hidden_id);
    	 $fileUrl='';
       if(!empty($notification_info)){
          $update_file = $request->file('image');
        //   dd($update_file);
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/merchant/';
	    	$update_file->move($uploadPath,$name);
	        $fileUrl .='https://flingex.com/'.$uploadPath.$name;
	    	$notification_info->image=$fileUrl;
    	}
        $notification_info->title=$request->title;
        $notification_info->descriptions=$request->description;
        $notification_info->save();
      Toastr::success('message', 'Merchant Notifications Updated successfully!');
       }
       else{
            $update_file = $request->file('image');
            // dd($update_file);
            
            $notification =new Merchant_notification;
           if($update_file){
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/merchant/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl .='https://flingex.com/'.$uploadPath.$name;
	    	$notification->image=$fileUrl;
       }
           $notification->title=$request->title;
           $notification->descriptions=$request->description;
           // $notification->image=$fileUrl;
           $notification->save();  
        define('API_ACCESS_KEY','AAAA9ItiJik:APA91bHUAAhp_dZzbOs1kmLNMD8HzwshQX39S1ZkQpAk6p2CG2WoPeRgg9dy8IYlGUU9OB0HYU0a5mnA5f-3X9hWfOicijaUy_HOgDCWuZoRng9qfmALn2lJ5NKDgrkDVx6SYr5xpVm5');

     $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

     $token = '/topics/flingex';


$notification =[
           "body"=> $request->title, 
           "title"=>$request->description,
           "image"=>$fileUrl,
          "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
          "id"=> "1",
          "status"=> "done",
          "type"=> "announcement",
          
   

];

// $extraNotificationData = ["message" => 'test',"moredata" =>'tes2'];

$fcmNotification = [
//'registration_ids' => $tokenList, //multple token array
'to'        =>$token, //single token
'notification' => $notification,
'data' => $notification,
'time_to_live'=>3600,
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

        
     Toastr::success('message', 'Merchant Notifications Create successfully!');

       }

        return back();

    }
    
     public function notification_delete($id){
        $offer=Merchant_notification::where('id',$id)->delete();
        Toastr::success('message', ' Notification Delete has been successfully!');
        return back();

    }
}
