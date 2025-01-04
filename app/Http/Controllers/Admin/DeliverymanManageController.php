<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Nearestzone;
use App\Models\Deliveryman;
use App\Models\Parcel;
use App\Models\Agent;
use DB;
class DeliverymanManageController extends Controller
{
    
    public function add(){
     $hub = Agent::where('status',1)->get();
    $areas = Nearestzone::where('status',1)->get();
    return view('backEnd.deliveryman.add',compact('areas','hub'));
   }
   
//   asign delivery man
 public function asingreport(Request $request){
    //  dd($request);
      $dates=$request->startDate;
      $datee=$request->endDate;
      $id=$request->agent;
      $deliveryman=Deliveryman::select('id','name')->get();
  // 		dd($agent);
      if ($request->agent  && $request->startDate==null && $request->endDate==null) {
   			$id=$request->agent;
        
        $parcelr =Parcel::where('deliverymanId',$request->agent)->where('status',4)->where('archive',1)->count();
        $parcelc =Parcel::where('deliverymanId',$request->agent)->where('status',9)->where('archive',1)->count();
        $parcelre =Parcel::where('deliverymanId',$request->agent)->where('status',8)->where('archive',1)->count();
        $parcelpa =Parcel::where('deliverymanId',$request->agent)->where('status',1)->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('deliverymanId',$request->agent)->where('status',2)->where('archive',1)->count();
        $parcelinterjit =Parcel::where('deliverymanId',$request->agent)->where('status',3)->where('archive',1)->count();
        $parcelhold =Parcel::where('deliverymanId',$request->agent)->where('status',5)->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('deliverymanId',$request->agent)->where('status',6)->where('archive',1)->count();
        $parcelrrhub =Parcel::where('deliverymanId',$request->agent)->where('status',7)->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('deliverymanId',$request->agent)->where('status','!=',9)->where('archive',1)->sum('cod');
       //dd($parcelprice);
        $deliveryCharge =Parcel::where('deliverymanId',$request->agent)->where('archive',1)->sum('deliveryCharge');
      
        $codCharge =Parcel::where('deliverymanId',$request->agent)->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('deliverymanId',$request->agent)->where('status',4)->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('deliverymanId',$request->agent)->where('archive',1)->count();
  
        $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.deliverymanId',$request->agent)
        ->where('parcels.archive',1)
              ->orderBy('parcels.id','DESC')
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get();
  
      }
      elseif($request->agent!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){ 
          $id=$request->agent;
          $status = $request->status;
        
        $parcelr =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelc =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelre =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelpa =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpictd =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelinterjit =Parcel::where('deliverymanId',$id)->where('status',$request->status)->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelhold =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrtupa =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
        $parcelrrhub =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
      
        $parcelpriceCOD =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      // dd($parcelprice);
        $deliveryCharge =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
      
        $codCharge =Parcel::where('status',$request->status)->where('deliverymanId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
      
        $Collectedamount =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
      
        $parcelcount =Parcel::where('deliverymanId',$request->agent)->where('status',$request->status)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
  
        $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.deliverymanId',$request->agent)->where('parcels.status',$request->status)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
        ->where('parcels.archive',1)
              ->orderBy('parcels.id','DESC')
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get();
      }
      else{
    //     $parcelr =Parcel::where('status',4)->where('archive',1)->count();
    //     $parcelc =Parcel::where('status',9)->where('archive',1)->count();
    //     $parcelre =Parcel::where('status',8)->where('archive',1)->count();
    //     $parcelpa =Parcel::where('status',1)->where('archive',1)->count();
      
    //     $parcelpictd =Parcel::where('status',2)->where('archive',1)->count();
    //     $parcelinterjit =Parcel::where('status',3)->where('archive',1)->count();
    //     $parcelhold =Parcel::where('status',5)->where('archive',1)->count();
    //     $parcelrrtupa =Parcel::where('status',6)->where('archive',1)->count();
    //     $parcelrrhub =Parcel::where('status',7)->where('archive',1)->count();
      
    //     $parcelpriceCOD =Parcel::where('status','!=',9)->where('archive',1)->sum('cod');
    //   //dd($parcelprice);
    //     $deliveryCharge= $parcelprice =Parcel::where('archive',1)->sum('deliveryCharge');
      
    //     $codCharge= $parcelprice =Parcel::where('archive',1)->sum('codCharge');
      
    //     $Collectedamount =Parcel::where('status',4)->where('archive',1)->sum('cod');
      
    //     $parcelcount =Parcel::where('archive',1)->count();
      $parcels = DB::table('parcels')
              ->join('merchants', 'merchants.id','=','parcels.merchantId')
              ->orderBy('parcels.id','DESC')
              ->where('parcels.archive',1)
              ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
              ->get()->take(20);
      }
    //  dd($parcels);
      return view('backEnd.deliveryman.asigndelivery')->with('deliveryman',@$deliveryman)->with('parcels',@$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount)->with('aid',@$id)->with('status',@$status)->with('dates',@$dates)->with('datee',@$datee);
    }
   
    public function save(Request $request){
        // return 1;
    	$this->validate($request,[
    		'name'=>'required',
    		'email'=>'required|unique:deliverymen',
    		'phone'=>'required|unique:deliverymen',
    		'designation'=>'required',
    		'area'=>'required',
    		'image'=>'required',
    		'password'=>'required',
            'status'=>'required',
    	]);
        
    	// image upload
    	$file = $request->file('image');
    	$name = time().$file->getClientOriginalName();
    	$uploadPath = 'public/uploads/deliveryman/';
    	$file->move($uploadPath,$name);
    	$fileUrl =$uploadPath.$name;

    	$store_data					=	new Deliveryman();
    	$store_data->name 			=	$request->name;
    	$store_data->agentId 		=	$request->hub;
    	$store_data->email  		= 	$request->email;
    	$store_data->phone  		= 	$request->phone;
    	$store_data->designation 	= 	$request->designation;
    	$store_data->area 			= 	$request->area;
    	$store_data->password 		= 	bcrypt(request('password'));
    	$store_data->image 			= 	$fileUrl;
    	$store_data->jobstatus 		= 	$request->jobstatus;
    	$store_data->status 		= 	$request->status;
    	$store_data->save();
        Toastr::success('message', 'Deliveryman add successfully!');
    	return redirect('admin/deliveryman/manage');
    }
   
   public function manage(){
    	$show_datas = DB::table('deliverymen')
    	->join('nearestzones', 'deliverymen.area', '=', 'nearestzones.id' )
    	->select('deliverymen.*', 'nearestzones.zonename')
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.deliveryman.manage',compact('show_datas'));
    }
    
    public function managepickupman(){
    	$show_datas = DB::table('deliverymen')
    	// ->join('nearestzones', 'deliverymen.area', '=', 'nearestzones.id' )
    	// ->select('deliverymen.*', 'nearestzones.zonename')
		->where('deliverymen.jobstatus',2)
		->where('deliverymen.status',1)
        ->orderBy('id','DESC')
    	->get();
		// dd($show_datas);exit;
    	return view('backEnd.deliveryman.manage',compact('show_datas'));
    }
    
 public function changepassword(Request $request){
     $thub= Deliveryman::where('id',$request->tid)->first();
     $thub->password=bcrypt(request('password'));
     $thub->save();
     Toastr::success('message', 'Change Password Update successfully!');
     return back();
 }

    public function edit($id){
        $edit_data = Deliveryman::find($id);
        $areas = Nearestzone::where('status',1)->get();
        $hub = Agent::where('status',1)->get();
        // dd($edit_data);exit;
    	return view('backEnd.deliveryman.edit',compact('edit_data','areas','hub'));
    }

    public function update(Request $request){
    	$this->validate($request,[
    		'name'=>'required',
    		'email'=>'required',
    		'phone'=>'required',
    		'designation'=>'required',
    		'area'=>'required',
    		'status'=>'required',
    	]);
    	$update_data = Deliveryman::find($request->hidden_id);
    	// image upload
    	$update_file = $request->file('image');
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/deliveryman/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
    	}else{
    		$fileUrl = $update_data->image;
    	}
// dd(	$request->jobstatus);exit;
    	$update_data->name 			=	$request->name;
    	$update_data->agentId 		=	$request->hub;
    	$update_data->email  		= 	$request->email;
    	$update_data->phone  		= 	$request->phone;
    	$update_data->designation 	= 	$request->designation;
    	$update_data->area 			= 	$request->area;
    	$update_data->password 		= 	bcrypt(request('password'));
    	$update_data->image 		= 	$fileUrl;
    	$update_data->jobstatus 	= 	$request->jobstatus;
    	$update_data->status 		= 	$request->status;
    	$update_data->save();
        Toastr::success('message', 'Employee update successfully!');
    	return redirect('admin/deliveryman/manage');
    }

    public function inactive(Request $request){
        $inactive_data = Deliveryman::find($request->hidden_id);
        $inactive_data->status=0;
        $inactive_data->save();
        Toastr::success('message', 'Employee inactive successfully!');
        return redirect('admin/deliveryman/manage');      
    }

    public function active(Request $request){
        $inactive_data = Deliveryman::find($request->hidden_id);
        $inactive_data->status=1;
        $inactive_data->save();
        Toastr::success('message', 'Employee active successfully!');
        return redirect('admin/deliveryman/manage');        
    }

    public function destroy(Request $request){
        $destroy_id = Deliveryman::find($request->hidden_id);
        $destroy_id->delete();
        Toastr::success('message', 'Employee delete successfully!');
        return redirect('admin/deliveryman/manage');         
    }
    
      public function commission(Request $request){
          $this->validate($request,[
    		'commission'=>'required|numeric',
    	]);
        $deliveryman_info = Deliveryman::find($request->hidden_id);
        
        	$deliveryman_info->commission 			=	$request->commission;
        	$deliveryman_info->save();
        
        Toastr::success('message', 'Commission Updated successfully!');
        return redirect('admin/deliveryman/manage');         
    }
    
     public function defaultcommission(Request $request){
          $this->validate($request,[
    		'commission'=>'required|numeric',
    	]);

       DB::table('deliverymen')->update(array('commission' => $request->commission));
        
        Toastr::success('message', 'Default Commission Updated successfully!');
        return redirect('admin/deliveryman/manage');         
    }
}
