<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Agent;
use App\Models\Parcel;
use App\Models\Pickup;
use App\Models\Deliveryman;
use App\Models\Transaction;
use App\Models\Merchant;
use App\Models\Parcelnote;
use App\Models\Parceltype;
use App\Exports\AgentParcelExport;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Session;
use DataTables;
use DB;
use Auth;
class AgentController extends Controller
{
   public function acceptReturn(Request $request){
    // dd($id);
    $parcel= Parcel::where('id',$request->hidden_id)->first();
    // dd($parcel);
    if($request->hubaprove==1){
        
   $parcel->hubaprove=$request->hubaprove;
      $parcel->deliverymanId=NULL;
      $parcel->save();
      $note = new Parcelnote();
      $note->parcelId = $request->hidden_id;            
      $note->note = "Agent Accepted this Parcel";
      $note->user=Session::get('agentName');
      $note->save();
        
     
    }else{
      $parcel->agentAprove=$request->hubaprove;
      $parcel->save();
      $note = new Parcelnote();
      $note->parcelId = $request->hidden_id;            
      $note->note = "Agent Rejected this Parcel";
      $note->user=Session::get('agentName');
      $note->save();
    }
    
    Toastr::success('message', 'Parcel has been accepted successfully!');
    return redirect()->back();


  }
  
  
   public function acceptReturn1(Request $request){
    // dd($id);
    $parcel= Parcel::where('id',$request->hidden_id)->first();
    // dd($parcel);
    if($request->hubaprove==1){
        
     $parcel->agentAprove=$request->hubaprove;
    //   $parcel->deliverymanId=NULL;
      $parcel->save();
      $note = new Parcelnote();
      $note->parcelId = $request->hidden_id;            
      $note->note = "Agent Accepted this Parcel";
      $note->user=Session::get('agentName');
      $note->save();
        
     
    }else{
      $parcel->agentAprove=$request->hubaprove;
      $parcel->save();
      $note = new Parcelnote();
      $note->parcelId = $request->hidden_id;            
      $note->note = "Agent Rejected this Parcel";
      $note->user=Session::get('agentName');
      $note->save();
    }
    
     return response()->json([
            'success' => 1,
        ], 200);


  }
    public function loginform(){
        return view('frontEnd.layouts.pages.agent.login');
    }
    public function login(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);
       $checkAuth =Agent::where('email',$request->email)
       ->first();
        if($checkAuth){
          if($checkAuth->status == 0){
             Toastr::warning('warning', 'Opps! your account has been suspends');
             return redirect()->back();
         }else{
          if(password_verify($request->password,$checkAuth->password)){
              $agentId = $checkAuth->id;
               $agentName = $checkAuth->name;
               Session::put('agentId',$agentId);
            Session::put('agentName',$agentName);
               Toastr::success('success', 'Thanks , You are login successfully');
              return redirect('/agent/dashboard');
            
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
    public function dashboard(){
         
        $totalAmount = Parcel::where(['agentId' => Session::get('agentId')])->where('archive',1)->sum('cod');
        $totalColected = Parcel::where(['agentId' => Session::get('agentId'),'status' => 4])->where('archive',1)->sum('cod');
        $totalTodayColected = Parcel::where(['agentId' => Session::get('agentId'), 'status' => 4])->whereDate('present_date', '=', date('Y-m-d'))->where('archive',1)->sum('cod');
        $transaction = Transaction::where('agent_id', Session::get('agentId'))->count();
        $paidAmount = Transaction::where('agent_id', Session::get('agentId'))->where('status',1)->sum('amount');
        $commition=0;
         $totalparcel=Parcel::where(['agentId'=>Session::get('agentId')])->where('archive',1)->count();
          $totaldelivery=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>4])->where('archive',1)->count();
          $totalhold=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>5])->where('archive',1)->count();
          $totalcancel=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>9])->where('archive',1)->count();
          $returnpendin=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>6])->where('archive',1)->count();
          $returnmerchant=Parcel::where(['agentId'=>Session::get('agentId'),'status'=>8])->where('archive',1)->count();
          return view('frontEnd.layouts.pages.agent.dashboard', compact('totalparcel','totalTodayColected', 'totaldelivery', 'totalhold', 'totalcancel', 'returnpendin', 'returnmerchant','transaction','totalAmount','totalColected','commition','paidAmount'));
     
    }
    
    //   parcel_request?
  public function parcels_request(Request $request){
       $daliveryman1=Deliveryman::where('agentId',Session::get('agentId'))->get();
       $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.agentAprove',null)
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('parcels.id','DESC')
        ->paginate(50);
        return view('frontEnd.layouts.pages.agent.parcel_request',compact('allparcel','daliveryman1'));

  }
    public function transaction(){
    $transaction=Transaction::where('agent_id',Session::get('agentId'))->orderBy('id','DESC')->get();
    $Collected =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->sum('cod');
    $Payamount =Transaction::where('agent_id',Session::get('agentId'))->sum('amount');

  

    $t=DB::table('parcels')->where('parcels.agentId',Session::get('agentId'))->where('parcels.status',4)->select(\DB::raw('SUM(parcels.cod) as collectedamount,parcels.present_date as date'))
    ->orderBy('parcels.present_date', 'DESC')
    ->groupBy(DB::raw('DATE(parcels.present_date)'))
    ->paginate(10);
    
    return view('frontEnd.layouts.pages.agent.transaction')->with('t',$t)->with('transaction',$transaction)->with('Payamount',$Payamount)->with('Collected',$Collected);
  }
  
   public function rider(Request $request){
        //   return response()->json([
        //     'success' => $request->dtrackid,
        // ], 200);
          $parcel = Parcel::where('trackingCode',$request->dtrackid)->where('agentAprove','1')->first();
          
          if($parcel)
          {
            $parcel->deliverymanId = $request->rider;
            $parcel->save();
            $note = new Parcelnote();
            $note->user=Session::get('agentName');
            $note->parcelId = $parcel->id;
            $note->mid = $parcel->merchantId;
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
  
  public function transactions(Request $request){
    $tran=new Transaction;
    $tran->agent_id=Session::get('agentId');
    $tran->amount=$request->amount;
    $tran->user=Session::get('agentName');
    $tran->type='admin';
    $tran->save();

  Toastr::success('message', 'Collected Amount has been send successfully!');
      return redirect()->back();
  }
  
  public function transaction_deliveryman(){
    $transaction=Transaction::where('hub_id',Session::get('agentId'))->orderBy('id','DESC')->get();
    return view('frontEnd.layouts.pages.agent.reportTranstion')->with('transaction',$transaction);
  }
//   bercode read
   public function track(Request $request)
    {
        $parcel1 = Parcel::where('trackingCode', $request->trackid)->where('agentId', Session::get('agentId'))->first();
          if ($request->status == 'a') {
                $parcel1->agentAprove = 1;
               $parcel1->save();

            } 
        
        // dd($parcel);
        $parcel = Parcel::where('trackingCode', $request->trackid)->where('agentId', Session::get('agentId'))->first();
        if ($parcel) {  
            $note = new Parcelnote();
            if ($request->status == 'a') {
                $parcel->agentAprove = 1;
                $note->note = 'Parcel has been accept successfully!';

            } else {
                // $parcel = Parcel::where('trackingCode', $request->trackid)->where('agentAprove','1')->where('agentId', Session::get('agentId'))->first();
                 if ($request->dliveid) {
                    $parcel->deliverymanId = $request->dliveid;
                  }
            if($request->status==3){
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel1->agentAprove = 1;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
            // $parcel1->agentAprove = 1;
            $parcel1->save();
            }
        $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
         if($request->status==2 && $deliverymanInfo!=NULL){
             	$parcel->picked_date =date("Y-m-d");
            	$parcel->update_by='Pick-'.Session::get('agentName');
            	$parcel->save();
            $merchantinfo =Agent::find($parcel->merchantId);
            $data = array(
             'contact_mail' => $merchantinfo->email,
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
         if($request->status==4){
            $merchantinfo = Merchant::find($parcel->merchantId);
            
            $parcel->delivered_at =date("Y-m-d");
            	$parcel->update_by=$parcel->update_by.',D-'.Session::get('agentName');
            	$parcel->save();
            $data = array(
             'contact_mail' => $merchantinfo->emailAddress,
             'trackingCode' => $parcel->trackingCode,
            );
            
            http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=Flingex&userName=Flingex&password=b57d05174707d151a9369e79af41a5c5&MsgType=TEXT&receiver=0{{$merchantinfo->phoneNumber}}&message=Your Message
            
            
             $send = Mail::send('frontEnd.emails.perceldeliverd', $data, function($textmsg) use ($data){
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Percel Deliverd Notification');
            });
        }
        if($request->status==8){
              //Return to Marchant status 8
          // $codcharge=$request->customerpay/100;
          $codcharge=0;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
        //   $parcel->codCharge=$codcharge;
          $parcel->cod=0;
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Session::get('agentName');
          $parcel->save();
          
          $validMerchant =Merchant::find($parcel->merchantId);
          $deliveryMan = Deliveryman::find($parcel->deliverymanId);
          $readytaka = $parcel->cod+$parcel->deliveryCharge;
       
       }
        //  if($request->status==8){
            
        //     $parcel->returnmerchant_date=date("Y-m-d");
        //     	$parcel->update_by=$parcel->update_by.',rtm-'.Session::get('agentName');
        //     	$parcel->save();
          
        // }
        
                $parcel->updated_time =date('Y-m-d H:i:s');
                $parcel->present_date =date("Y-m-d");
                $parcel->user = $parcel->user.','.Session::get('agentName');
                $parcel->status = $request->status;
                $pstatus= Parceltype::where('id',$request->status)->first();
                $note->note = 'Parcel has been '. $pstatus->title . ' successfully!';

            }
            // $parcel->status = $request->status;
            $parcel->save();
            // if ($request->status == 'a') {

            // } else {

            // }
            $note->parcelId = $parcel->id;
            $note->user = Session::get('agentName');
            // $note->user=Auth::user()->username;
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
        if (request()->ajax()) {
            if ($request->filter_id != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    // ->where('parcels.trackingCode', $request->trackId)
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                    ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
                    ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',1)
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
 
                            
                            
                            
                            <li><button class="thumbs_up status" title="Action" data-toggle="modal" sid="' . $row->id . '" statusids="' . $row->status . '" customer_phone="' . $row->recipientPhone . '" data-target="#sUpdateModal"><i class="fa fa-sync-alt"></i></button></li>
                            <li><a class="edit_icon " a href="/editor/parcel/invoice/' . $row->id . '" title="Invoice" target="_blank"><i class="fa fa-file-invoice"></i></a></li>

                             
                            </ul>';

                    return $button;
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
                 
                
                ->rawColumns(['date','deliveyrman', 'agent', 'action', 'subtotal','status'])
                ->make(true);
        }
        
         return view('frontEnd.layouts.pages.agent.test');

    }
    public function parcel_request(Request $request){
        if (request()->ajax()) {
            if ($request->filter_id != null) {
                $show_data = DB::table('parcels')
                    ->leftJoin('merchants', 'merchants.id', '=', 'parcels.merchantId')
                    ->leftJoin('nearestzones', 'parcels.reciveZone', '=', 'nearestzones.id')
                    ->leftJoin('deliverymen', 'parcels.deliverymanId', '=', 'deliverymen.id')
                    ->leftJoin('agents', 'parcels.agentId', '=', 'agents.id')
                    ->leftJoin('parceltypes', 'parcels.status', '=', 'parceltypes.id')
                    // ->where('parcels.trackingCode', $request->trackId)
                    ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                    ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                     ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
                    ->where('parcels.agentId',Session::get('agentId'))
                    ->where('parcels.archive',1)
                    ->where('parcels.agentAprove',null)
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
 
                            
                            
                            
                            <li>
                             <button class="btn btn-sm btn-info accepeted" title="Parcel Accepeted Or Rejected" data-toggle="modal" sid="' . $row->id . '" data-target="#pAccOrRej"><i class="fa fa-sync-alt"></i></button>
                            </li>
                            

                             
                            </ul>';

                    return $button;
                })
                 
                ->addColumn('deliveyrman', function ($row) {
                    $deliveryMan = '<span>' . $row->dname . '</span><br>
                    ';
                    return $deliveryMan;
                    // return ($row->cod-$row->deliveryCharge);
                })
                ->addColumn('agent', function ($row) {
                    $agent = '<span>' . $row->hubname . '</span><br>
                    ';
                    return $agent;
                    // return ($row->cod-$row->deliveryCharge);
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
                 
                
                ->rawColumns(['date','deliveyrman', 'agent', 'action', 'subtotal','status'])
                ->make(true);
        }
        
         return view('frontEnd.layouts.pages.agent.requestParcel');

    }    
    public function oldparcel(Request $request){
        // $daliveryman1=Deliveryman::where('agentId',Session::get('agentId'))->get();
        $daliveryman1=Deliveryman::get();
        $dates= $request->startDate;
        $datee= $request->endDate;
        // dd($request);
          $aparceltypes = Parceltype::where('slug',$request->slug)->first();
       $filter = $request->filter_id;
   
       if($request->trackId!=NULL){
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('trackingCode',$request->trackId)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('trackingCode',$request->trackId)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
       }elseif($request->phoneNumber!=NULL){
           
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->paginate(150);
       }elseif($request->trackId!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
           
          $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('recipientPhone',$request->phoneNumber)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
           
       }else{
            $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->count();
        
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('parcels.id','DESC')
        ->paginate(150);
       }
       $aparceltypes = Parceltype::limit(8)->get();
      return view('frontEnd.layouts.pages.agent.parcels',compact('daliveryman1','allparcel','aparceltypes','parcelr','parcelc','parcelre','parcelpa','parcelpictd','parcelinterjit','parcelhold','parcelrrtupa','parcelrrhub','parcelpriceCOD','deliveryCharge','codCharge','Collectedamount','parcelcount','dates','datee'));
  }
   public function parcels(Request $request){
    //   dd($request);
        // $daliveryman1=Deliveryman::where('agentId',Session::get('agentId'))->get();    
        $daliveryman1=Deliveryman::get();  

         $dates= $request->startDate;
        $datee= $request->endDate;
        // dd($request);
          $aparceltypes = Parceltype::where('slug',$request->slug)->first();
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
   
           
    //      $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('trackingCode',$request->trackId)->where('archive',1)->count();
      
    //     $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('trackingCode',$request->trackId)->where('archive',1)->count();
    //     $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('trackingCode',$request->trackId)->where('archive',1)->count();
      
    //     $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
    //   // dd($parcelprice);
    //     $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('deliveryCharge');
      
    //     $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('archive',1)->sum('codCharge');
      
    //     $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('trackingCode',$request->trackId)->where('archive',1)->sum('cod');
      
    //     $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('trackingCode',$request->trackId)->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.trackingCode',$request->trackId)
        // ->orWhere('parcels.hubaprove',2)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
        
       }elseif($request->phoneNumber!=NULL){
    //       $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
      
    //     $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
    //     $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
      
    //     $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
    //   // dd($parcelprice);
    //     $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('deliveryCharge');
      
    //     $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('codCharge');
      
    //     $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('recipientPhone',$request->phoneNumber)->where('archive',1)->sum('cod');
      
    //     $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('recipientPhone',$request->phoneNumber)->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        //   return 9;
    //         $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->count();
      
    //     $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    //     $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
    //     $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
    //   // dd($parcelprice);
    //     $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
    //     $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
    //     $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
    //     $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)
        ->where('parcels.archive',1)
        ->whereBetween('parcels.present_date',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->paginate(150);
       }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.present_date',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->paginate(150);
       }else{
    //     $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',4)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',9)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',8)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('archive',1)->count();
      
    //     $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',2)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',3)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',5)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',6)->where('archive',1)->where('agentAprove',1)->count();
    //     $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status',7)->where('archive',1)->where('agentAprove',1)->count();
      
    //     $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('status','!=',9)->where('archive',1)->where('agentAprove',1)->sum('cod');
    //   // dd($parcelprice);
    //     $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('agentAprove',1)->sum('deliveryCharge');
     
    //     $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('agentAprove',1)->sum('codCharge');
      
    //     $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('agentAprove',1)->sum('cod');
      
    //     $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('parcels.status',$aparceltypes->id)->where('archive',1)->where('agentAprove',1)->count();
        
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.status',$aparceltypes->id)
        ->where('parcels.agentAprove',1)
        ->where('parcels.archive',1)
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('parcels.id','DESC')->
     paginate(50);
       }
    //   ,'aparceltypes','parcelr','parcelc','parcelre','parcelpa','parcelpictd','parcelinterjit','parcelhold','parcelrrtupa','parcelrrhub','parcelpriceCOD','deliveryCharge','codCharge','Collectedamount','parcelcount'
       $aparceltypes = Parceltype::limit(8)->get();
      return view('frontEnd.layouts.pages.agent.parcels',compact('daliveryman1','allparcel','dates','datee','aparceltypes'));
  }
  
  
  //Hub Request Start
  public function hubRequest(Request $request){
    //   dd($request);
        // $daliveryman1=Deliveryman::where('agentId',Session::get('agentId'))->get();    
        $daliveryman1=Deliveryman::get();  

         $dates= $request->startDate;
        $datee= $request->endDate;
        // dd($request);
          $aparceltypes = Parceltype::where('slug',$request->slug)->first();
       $filter = $request->filter_id;
       if($request->trackId!=NULL){
   
           
       
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        // ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.trackingCode',$request->trackId)
         ->where('parcels.hubaprove',2)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
        
       }elseif($request->phoneNumber!=NULL){
         
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        // ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.hubaprove',2)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
       ->paginate(150);
       }elseif($request->startDate!=NULL && $request->endDate!=NULL){
        //   return 9;
            $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        // ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.hubaprove',2)
        ->whereBetween('parcels.present_date',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->paginate(150);
       }elseif($request->phoneNumber!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        // ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.hubaprove',2)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.present_date',[$request->startDate, $request->endDate])
        ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->paginate(150);
       }else{
        
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        // ->where('parcels.agentId',Session::get('agentId'))
        ->where('parcels.archive',1)
        ->where('parcels.hubaprove',2)
        ->select('parcels.*','merchants.companyName','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress')
        ->orderBy('parcels.id','DESC')->
     paginate(50);
       }
       $aparceltypes = Parceltype::limit(8)->get();
      return view('frontEnd.layouts.pages.agent.parcel_request',compact('daliveryman1','allparcel','aparceltypes','dates','datee'));
  }
  
  //Hub Request End
  public function accept($id){
    // dd($id);
    $parcel= Parcel::where('id',$id)->first();
    // dd($parcel);
    $parcel->agentAprove=1;
    $parcel->save();
    Toastr::success('message', 'Parcel has been accept successfully!');
      return redirect()->back();


  }
   public function invoice($id){
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    ->where('parcels.agentId',Session::get('agentId'))
    ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
    ->where('parcels.id',$id)
    ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
    ->first();
        if($show_data!=NULL){
        	return view('frontEnd.layouts.pages.agent.invoice',compact('show_data'));
        }else{
          Toastr::error('Opps!', 'Your process wrong');
          return redirect()->back();
        }
    }
  public function delivermanasiagn(Request $request){
      $this->validate($request,[
        'deliverymanId'=>'required',
      ]);
      $parcel = Parcel::find($request->hidden_id);
      $parcel->deliverymanId = $request->deliverymanId;
      $parcel->save();
      
      if($request->note){
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;
            $note->note = $request->note;
            $note->save();
        }

      Toastr::success('message', 'A deliveryman asign successfully!');
      return redirect()->back();
      $deliverymanInfo = Deliveryman::find($parcel->deliverymanId);
      $merchantinfo =Agent::find($parcel->merchantId);
      $data = array(
       'contact_mail' => $merchantinfo->email,
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
  public function return_Central_hub(Request $request){
      $parcel = Parcel::where('id',$request->hidden_id)->first();
      $parcel->agentId=$request->central_hub;
      $parcel->save();
      Toastr::success('message', 'Send to Parcel  Central Hub successfully!');
      return redirect()->back();
  }
  public function statusupdate(Request $request){
        // dd($request);
    	$this->validate($request,[
         // 'status'=>'required',
    	]); 
    	$parcel1 = Parcel::where('id',$request->hidden_id)->first();
     $parceltype=Parceltype::where('id',$request->pstatus)->first();
     
     $statuscheck= Parcelnote::where('parcelId',$request->hidden_id)->where('parcelStatus',$parceltype->title)->first();
     if($statuscheck){
         return response()->json([
            'success' => 3, 
        ], 200);
     }
     else{
        $parcel = Parcel::where('id',$request->hidden_id)->first();
        
        if($request->pstatus==4){
            //devivered status 4
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->delivered_at=date("Y-m-d");
        	$parcel->update_by=$parcel->update_by.',D-'.Session::get('agentName');
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
          $parcel->update_by=$parcel->update_by.',rtm-'.Session::get('agentName');
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
         elseif($request->pstatus==10){
          
            // $codcharge=$request->customerpay/100;
        $parcel->status=$request->pstatus;
      $parcel->save();
      $note1 = new Parcelnote();
      $note1->parcelId = $request->hidden_id;
      $note1->mid = $parcel->merchantId;
      $note1->note = Session::get('agentName')." Request Return to Central Hub this Parcel";
      $note1->parcelStatus = "Request Return to central Hub";
      $note1->user=Session::get('agentName');
      $note1->save();
      
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
            	$parcel->update_by='Pick-'.Session::get('agentName');
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
        
       
        
    	
    	$parcel->status = $request->pstatus;
    	$parcel->present_date =date("Y-m-d");
    	$parcel->updated_time =date('Y-m-d H:i:s');
    	$parcel->user=$parcel->user.','.Session::get('agentName');
    	$parcel->save();

             
            $note = new Parcelnote();
            $note->parcelId = $request->hidden_id;            
            $note->note = $request->snote;
            $note->mid = $parcel->merchantId;
            $note->cnote = $request->note;
            $note->eta = $request->eta;
            $note->parcelStatus = $parceltype->title;
            $note->user=Session::get('agentName');
            $note->save();
            
        
    	 return response()->json([
            'success' => 1,
        ], 200);
     }
    }
  public function oldstatusupdate(Request $request){
    //   return $request->all();
    // dd($request, Session::get('agentName'));
    
      $this->validate($request,[
        'status'=>'required',
      ]); 
       $parceltype=Parceltype::where('id',$request->status)->first();
      $parcel = Parcel::find($request->hidden_id);
      if($request->status==10){
          
            // $codcharge=$request->customerpay/100;
        $parcel->status=$request->status;
      $parcel->save();
      $note = new Parcelnote();
      $note->parcelId = $request->hidden_id;
      $note->mid = $parcel->merchantId;
      $note->note = Session::get('agentName')." Request Return to Central Hub this Parcel";
      $note->user=Session::get('agentName');
      $note->save();
      Toastr::success('message', 'Your Return to Central Hub Request successfully!');
      return redirect()->back();
        }

        if($request->status==3){
            // $codcharge=$request->customerpay/100;
            $codcharge=0;
            $parcel->merchantAmount=($parcel->merchantAmount)-($codcharge);
            $parcel->merchantDue=($parcel->merchantAmount)-($codcharge);
            // $parcel->codCharge=$codcharge;
            $parcel->save();
        }
        $deliverymanInfo =Deliveryman::where(['id'=>$parcel->deliverymanId])->first();
         if($request->status==2 && $deliverymanInfo!=NULL){
            $merchantinfo =Agent::find($parcel->merchantId);
            
            // $data = array(
            //  'contact_mail' => $merchantinfo->email,
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
            $parcel->picked_date =date("Y-m-d");
            	$parcel->update_by='Pick-'.Session::get('agentName');
            	$parcel->save();
        }
         if($request->status==4){
            $merchantinfo = Merchant::find($parcel->merchantId);
            $parcel->delivered_at =date("Y-m-d");
            	$parcel->update_by=$parcel->update_by.',D-'.Session::get('agentName');
            	$parcel->save();
            // $data = array(
            //  'contact_mail' => $merchantinfo->emailAddress,
            //  'trackingCode' => $parcel->trackingCode,
            // );
            
            http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=Flingex&userName=Flingex&password=b57d05174707d151a9369e79af41a5c5&MsgType=TEXT&receiver=0{{$merchantinfo->phoneNumber}}&message=Your Message
            
            
            //  $send = Mail::send('frontEnd.emails.perceldeliverd', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@flingex.com');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Percel Deliverd Notification');
            // });
        }
        if($request->status==8){
              //Return to Marchant status 8
          // $codcharge=$request->customerpay/100;
          $codcharge=0;
          $parcel->merchantAmount=0;
          $parcel->merchantDue=0;
        //   $parcel->codCharge=$codcharge;
          $parcel->cod=0;
          $parcel->returnmerchant_date=date("Y-m-d");
          $parcel->update_by=$parcel->update_by.',rtm-'.Session::get('agentName');
          $parcel->save();
          
          $validMerchant =Merchant::find($parcel->merchantId);
          $deliveryMan = Deliveryman::find($parcel->deliverymanId);
          $readytaka = $parcel->cod+$parcel->deliveryCharge;
       
       }
         
      $parcel->status = $request->status;
      $parcel->updated_time =date('Y-m-d H:i:s');
      $parcel->present_date =date("Y-m-d");
      $parcel->save();
     
      
          $note = new Parcelnote();
          $note->parcelId = $request->hidden_id;  
           $note->note = $request->snote;
        $note->cnote = $request->note;
         $note->mid = $parcel->merchantId;
          $note->parcelStatus = $parceltype->title;
           $note->user=Session::get('agentName');
        //   $note->user=Auth::user()->username;
          $note->save();
      Toastr::success('message', 'Parcel information update successfully!');
      return redirect()->back();
    }
	public function logout(){
      Session::flush();
      Toastr::success('Success!', 'Thanks! you are logout successfully');
      return redirect('agent/logout');
  }
 public function pickup(){
      $show_data = DB::table('pickups')
      ->where('pickups.agent',Session::get('agentId'))
      ->orderBy('pickups.id','DESC')
      ->select('pickups.*')
      ->get();
      $deliverymen = Deliveryman::where('status',1)->get();
      return view('frontEnd.layouts.pages.agent.pickup',compact('show_data','deliverymen'));
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
    
        if($request->status==2){
            $deliverymanInfo =Deliveryman::where(['id'=>$pickup->deliveryman])->first();
            $parcel->picked_date =date("Y-m-d");
            	$parcel->update_by='Pick-'.Session::get('agentName');
            	$parcel->save();
            // $data = array(
            //  'name' => $deliverymanInfo->name,
            //  'companyname' => $merchantInfo->companyName,
            //  'phone' => $deliverymanInfo->phone,
            //  'address' => $merchantInfo->pickLocation,
            // );
            // $send = Mail::send('frontEnd.emails.pickupdeliveryman', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@flingex.com');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Pickup request update');
            // });
        }
      Toastr::success('message', 'Pickup status update successfully!');
      return redirect()->back();
    }
   public function passreset(){
      return view('frontEnd.layouts.pages.agent.passreset');
    }
    public function passfromreset(Request $request){
      $this->validate($request,[
            'email' => 'required',
        ]);
        $validAgent =Agent::Where('email',$request->email)
       ->first();
        if($validAgent){
             $verifyToken=rand(111111,999999);
             $validAgent->passwordReset  = $verifyToken;
             $validAgent->save();
             Session::put('resetAgentId',$validAgent->id);
             
             $data = array(
             'contact_mail' => $validAgent->email,
             'verifyToken' => $verifyToken,
            );
            $send = Mail::send('frontEnd.layouts.pages.agent.forgetemail', $data, function($textmsg) use ($data){
             $textmsg->from('info@flingex.com');
             $textmsg->to($data['contact_mail']);
             $textmsg->subject('Forget password token');
            });
          return redirect('agent/resetpassword/verify');
        }else{
              Toastr::error('Sorry! You have no account', 'warning!');
             return redirect()->back();
        }
    }
    public function saveResetPassword(Request $request){
       $validAgent =Agent::find(Session::get('resetAgentId'));
        if($validAgent->passwordReset==$request->verifyPin){
           $validAgent->password   = bcrypt(request('newPassword'));
           $validAgent->passwordReset  = NULL;
             $validAgent->save();
             
             Session::forget('resetAgentId');
             Session::put('agentId',$validAgent->id);
             Toastr::success('Wow! Your password reset successfully', 'success!');
             return redirect('agent/dashboard');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
             return redirect()->back();
        }
       
    }
    public function resetpasswordverify(){
        if(Session::get('resetAgentId')){
        return view('frontEnd.layouts.pages.agent.passwordresetverify');
        }else{
            Toastr::error('Sorry! Your process something wrong', 'warning!');
            return redirect('forget/password');
        }
    }
    public function export( Request $request ) {
        return Excel::download( new AgentParcelExport(), 'parcel.xlsx') ;
    
    }
    public function report(Request $request){
      if ($request->startDate) {
      
        
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
        $codCharge =Parcel::where('agentId',Session::get('agentId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
  
        $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')
                ->where('parcels.agentId',Session::get('agentId'))->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
                ->where('parcels.archive',1)
              ->orderBy('parcels.id','DESC')
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get();
  
      }
      else{
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->count();

      $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')->where('parcels.agentId',Session::get('agentId'))
              ->where('parcels.archive',1)
              ->orderBy('parcels.id','DESC')
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get();
      }
      
      return view('frontEnd.layouts.pages.agent.report')->with('parcels',$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount);
    }
    public function asingreport(Request $request){
      $dates=$request->startDate;
      $datee=$request->endDate;
      $id=$request->agent;
      $deliveryman=Deliveryman::where('agentId',Session::get('agentId'))->get();
  // 		dd($agent);
      if ($request->agent  && $request->startDate==null && $request->endDate==null) {
  // 			$id=$request->agent;
        
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',4)->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',9)->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',8)->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',1)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',2)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',3)->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',5)->where('archive',1)->count();
        $parcelrrtupa =where('agentId',Session::get('agentId'))->Parcel::where('deliverymanId',$request->agent)->where('status',6)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',7)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status','!=',9)->where('archive',1)->sum('cod');
       //dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',4)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('archive',1)->count();
  
        $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')->where('agentId',Session::get('agentId'))
            ->where('parcels.deliverymanId',$request->agent)
        ->where('parcels.archive',1)
              ->orderBy('parcels.id','DESC')
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get();
  
      }
      elseif($request->agent!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){ 
          $id=$request->agent;
          $status = $request->status;
        
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$id)->where('status',$request->status)->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('status',$request->status)->where('deliverymanId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('deliverymanId',$request->agent)->where('status',$request->status)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
  
        $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')->where('agentId',Session::get('agentId'))
        ->where('parcels.deliverymanId',$request->agent)->where('parcels.status',$request->status)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
        ->where('parcels.archive',1)
              ->orderBy('parcels.id','DESC')
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get();
      }
      else{
        $parcelr =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('archive',1)->count();
        $parcelc =Parcel::where('agentId',Session::get('agentId'))->where('status',9)->where('archive',1)->count();
        $parcelre =Parcel::where('agentId',Session::get('agentId'))->where('status',8)->where('archive',1)->count();
        $parcelpa =Parcel::where('agentId',Session::get('agentId'))->where('status',1)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('agentId',Session::get('agentId'))->where('status',2)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('agentId',Session::get('agentId'))->where('status',3)->where('archive',1)->count();
        $parcelhold =Parcel::where('agentId',Session::get('agentId'))->where('status',5)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('agentId',Session::get('agentId'))->where('status',6)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('agentId',Session::get('agentId'))->where('status',7)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('agentId',Session::get('agentId'))->where('status','!=',9)->where('archive',1)->sum('cod');
       //dd($parcelprice);
        $deliveryCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('deliveryCharge');
      
        $codCharge= $parcelprice =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('agentId',Session::get('agentId'))->where('status',4)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('agentId',Session::get('agentId'))->where('archive',1)->count();
      $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')
              ->orderBy('parcels.id','DESC')->where('parcels.agentId',Session::get('agentId'))
              ->where('parcels.archive',1)
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get()->take(10);
      }
      
      return view('frontEnd.layouts.pages.agent.asigndelivery')->with('deliveryman',@$deliveryman)->with('parcels',@$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount)->with('aid',@$id)->with('status',@$status)->with('dates',@$dates)->with('datee',@$datee);
    }
    
    // agentasign?
    public function agentasign(Request $request){
     $this->validate($request, [
            'agentId' => 'required',
        ]);
        $parcel = Parcel::find($request->hidden_id);
        if ($parcel) {
         
                $note = new Parcelnote();
                $note->user=Session::get('agentName');
                $note->parcelId = $request->hidden_id;
                $note->note="Change Hub form ".Session::get('agentName'); 
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
    
}
