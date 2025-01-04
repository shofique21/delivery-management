<?php

namespace App\Http\Controllers\editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Pickup;
use App\Models\Codcharge;
use App\Models\Merchant;
use App\Models\Deliveryman;
use App\Models\PickDrop;
use DB;
use App\Models\Post;
use Mail;
use Auth;
use Exception;
class PickupManageController extends Controller
{
   
    public function newpickup(){
      
    	$show_data = DB::table('pickups')
    	->where('pickups.status',0)
    	->orderBy('pickups.id','DESC')
    	->select('pickups.*')
    	->get();
    	return view('backEnd.pickup.new',compact('show_data'));
    }
 

    public function pendingpickup(){
          
    	$show_data = DB::table('pickups')
    	->join('deliverymen','pickups.deliveryman','=','deliverymen.id')
    	->where('pickups.status',1)
    	->orderBy('pickups.id','DESC')
    	->select('pickups.*','deliverymen.name','deliverymen.phone')
    	->get();
    	return view('backEnd.pickup.pending',compact('show_data'));
    }

    public function acceptedpickup(){
      
    	$show_data = DB::table('pickups')
    	->join('deliverymen','pickups.deliveryman','=','deliverymen.id')
    	 ->where('pickups.status',2)
    	->orderBy('pickups.id','DESC')
    	->select('pickups.*','deliverymen.name','deliverymen.phone')
    	->get();
    	return view('backEnd.pickup.accepted',compact('show_data'));
    }

    public function cancelled(){
    	$show_data = DB::table('pickups')
    	->join('deliverymen','pickups.deliveryman','=','deliverymen.id')
    	 ->where('pickups.status',3)
    	->orderBy('pickups.id','DESC')
    	->select('pickups.*','deliverymen.name','deliverymen.phone')
    	->get();
    	return view('backEnd.pickup.cancelled',compact('show_data'));
    }
    public function agentmanasign(Request $request){
    	$this->validate($request,[
    		'agent'=>'required',
    	]);
    	$parcel = Pickup::find($request->hidden_id);
    	$parcel->agent = $request->agent;
    	$parcel->save();
        Toastr::success('message', 'Pickup agent update successfully!');
        return redirect()->back();

    }

    public function statusupdate(Request $request){
    	$this->validate($request,[
    		'status'=>'required',
    	]);
    	$pickup = Pickup::find($request->hidden_id);
    	$pickup->status = $request->status;
    	$pickup->save();
    
        if($request->status==2){
            $deliverymanInfo =Deliveryman::where(['id'=>$pickup->deliveryman])->first();
            // $data = array(
            //  'name' => $deliverymanInfo->name,
            //  'companyname' => $merchantInfo->companyName,
            //  'phone' => $deliverymanInfo->phone,
            //  'address' => $merchantInfo->pickLocation,
            // );
            // $send = Mail::send('frontEnd.emails.pickupdeliveryman', $data, function($textmsg) use ($data){
            //  $textmsg->from('info@hazicourier.com.bd');
            //  $textmsg->to($data['contact_mail']);
            //  $textmsg->subject('Pickup request update');
            // });
        }
    	Toastr::success('message', 'Pickup information update successfully!');
    	return redirect()->back();
    }
    public function pickdrop(){
// return 1;
		$show_data= PickDrop::orderBy('id','DESC')->get();
		return view('backEnd.pickup.pickdrop',compact('show_data'));

	}
}
