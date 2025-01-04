<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Nearestzone;
use App\Models\Agent;
use App\Models\Parcel;
use App\Models\Transaction;
use DB;
class AgentManageController extends Controller
{
    public function add(){
    $areas = Nearestzone::where('status',1)->get();
    return view('backEnd.agent.add',compact('areas'));
   }
    public function transactions(){
	$transaction=Transaction::where('type','admin')->orderBy('id','DESC')->get();
	return view('backEnd.agent.transaction',compact('transaction'));
   }
   
    public function save(Request $request){
    	$this->validate($request,[
    		'name'=>'required',
    		'email'=>'required|unique:agents',
    		'phone'=>'required|unique:agents',
    		'designation'=>'required',
    		'area'=>'required',
    		'image'=>'required',
    		'password'=>'required',
            'status'=>'required',
    	]);
        
    	// image upload
    	$file = $request->file('image');
    	$name = time().$file->getClientOriginalName();
    	$uploadPath = 'public/uploads/agent/';
    	$file->move($uploadPath,$name);
    	$fileUrl =$uploadPath.$name;

    	$store_data					=	new Agent();
    	$store_data->name 			=	$request->name;
    	$store_data->type 			=	$request->type;
    	$store_data->email  		= 	$request->email;
    	$store_data->phone  		= 	$request->phone;
    	$store_data->designation 	= 	$request->designation;
    	$store_data->area 			= 	$request->area;
    	$store_data->password 		= 	bcrypt(request('password'));
    	$store_data->image 			= 	$fileUrl;
    	$store_data->status 		= 	$request->status;
    	$store_data->save();
        Toastr::success('message', 'Agent add successfully!');
    	return redirect('admin/agent/manage');
    }
    
 public function changepassword(Request $request){
    //  dd($request);
     $thub= Agent::where('id',$request->hidden_id)->first();
     $thub->password=bcrypt(request('changepassword'));
     $thub->save();
     Toastr::success('message', 'Change Password Update successfully!');
     return back();
 }
   
   public function manage(){
    	$show_datas = DB::table('agents')
    	->join('nearestzones', 'agents.area', '=', 'nearestzones.id' )
    	->select('agents.*', 'nearestzones.zonename')->where('agents.type',null)
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.agent.manage',compact('show_datas'));
    }
public function thirdpartyagent(){
    	$show_datas = DB::table('agents')
    	->join('nearestzones', 'agents.area', '=', 'nearestzones.id' )
    	->select('agents.*', 'nearestzones.zonename')->where('agents.type','third_party_agent')
        ->orderBy('id','DESC')
    	->get();
    	return view('backEnd.agent.tmanage',compact('show_datas'));
    }
    public function edit($id){
        $edit_data = Agent::find($id);
        $areas = Nearestzone::where('status',1)->get();
    	return view('backEnd.agent.edit',compact('edit_data','areas'));
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
    	$update_data = Agent::find($request->hidden_id);
    	// image upload
    	$update_file = $request->file('image');
    	if ($update_file) {
	    	$name = time().$update_file->getClientOriginalName();
	    	$uploadPath = 'public/uploads/agent/';
	    	$update_file->move($uploadPath,$name);
	    	$fileUrl =$uploadPath.$name;
    	}else{
    		$fileUrl = $update_data->image;
    	}

    	$update_data->name 			=	$request->name;
    	$update_data->type 			=	$request->type;
    	$update_data->email  		= 	$request->email;
    	$update_data->phone  		= 	$request->phone;
    	$update_data->designation 	= 	$request->designation;
    	$update_data->area 			= 	$request->area;
    	$update_data->password 		= 	bcrypt(request('password'));
    	$update_data->image 		= 	$fileUrl;
    	$update_data->status 		= 	$request->status;
    	$update_data->save();
        Toastr::success('message', 'Employee update successfully!');
    	return redirect('admin/agent/manage');
    }

    public function inactive(Request $request){
        $inactive_data = Agent::find($request->hidden_id);
        $inactive_data->status=0;
        $inactive_data->save();
        Toastr::success('message', 'Employee inactive successfully!');
        return redirect('admin/agent/manage');      
    }

    public function active(Request $request){
        $inactive_data = Agent::find($request->hidden_id);
        $inactive_data->status=1;
        $inactive_data->save();
        Toastr::success('message', 'Employee active successfully!');
        return redirect('admin/agent/manage');        
    }

    public function destroy(Request $request){
        $destroy_id = Agent::find($request->hidden_id);
        $destroy_id->delete();
        Toastr::success('message', 'Employee delete successfully!');
        return redirect('admin/agent/manage');         
    }
    
   	public function hubreport(Request $request){
		$dates=$request->startDate;
		$datee=$request->endDate;
		$id=$request->agent;
		$agent=Agent::get();
			  $Collectedamount =0;
		$deliveryCharge= 0;
		  $codCharge=0;
// 		dd($agent);
		if ($request->agent=='allagent') {
		      $parcelpriceCOD =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('cod');
	   //dd($parcelprice);
		  //$deliveryCharge= Parcel::where('agentId',$request->agent)->sum('deliveryCharge');
		  
	  
		  //$codCharge= Parcel::where('agentId',$request->agent)->sum('codCharge');
		
	  
		  //$Collectedamount =Parcel::where('agentId',$request->agent)->where('status',4)->sum('cod');
		  
// 			$id=$request->agent;
			$all=Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->get();
			foreach($all as $p){
			    $deliveryCharge+=$p->deliveryCharge;
			    $codCharge+=$p->codCharge;
			    $Collectedamount+=$p->cod;
			    
			}
			$parcelr =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',4)->where('archive',1)->count();
		  $parcelc =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',9)->where('archive',1)->count();
		  $parcelre =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',8)->where('archive',1)->count();
		  $parcelpa =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',1)->where('archive',1)->count();
	  
		  $parcelpictd =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',2)->where('archive',1)->count();
		  $parcelinterjit =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',3)->where('archive',1)->count();
		  $parcelhold =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',5)->where('archive',1)->count();
		  $parcelrrtupa =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',6)->where('archive',1)->count();
		  $parcelrrhub =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status',7)->where('archive',1)->count();
	  
		
	  
		  $parcelcount =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

			$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->whereBetween('present_date', [$request->startDate, $request->endDate])
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
        // $parcels->appends(['agent'=>$request->agent,'startDate' => $request->startDate ,'endDate' => $request->endDate]);
		}
		elseif($request->agent!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){ 
		    $id=$request->agent;
			
			$parcelr =Parcel::where('agentId',$request->agent)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelc =Parcel::where('agentId',$request->agent)->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelre =Parcel::where('agentId',$request->agent)->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelpa =Parcel::where('agentId',$request->agent)->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
	  
		  $parcelpictd =Parcel::where('agentId',$request->agent)->where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelinterjit =Parcel::where('agentId',$id)->where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelhold =Parcel::where('agentId',$request->agent)->where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelrrtupa =Parcel::where('agentId',$request->agent)->where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelrrhub =Parcel::where('agentId',$request->agent)->where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
	  
		  $parcelpriceCOD =Parcel::where('agentId',$request->agent)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
	  // dd($parcelprice);
		  //$deliveryCharge= Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('deliveryCharge');
	  
		  //$codCharge= Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('codCharge');
	  
		  //$Collectedamount =Parcel::where('agentId',$request->agent)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->sum('cod');
	  
		  $parcelcount =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
	$all=Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->where('status',4)->get();
			foreach($all as $p){
			    $deliveryCharge+=(int)$p->deliveryCharge;
			    $codCharge+=(int)$p->codCharge;
			    $Collectedamount+=$p->cod;
			    
			}
			$parcels = DB::table('parcels')
	
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->where('parcels.agentId',$request->agent)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
            //  $parcels->appends(['agent'=>$request->agent,'startDate' => $request->startDate ,'endDate' => $request->endDate]);
		}
		else{
		$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
             ->whereMonth('parcels.created_at', date('m'))
             ->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get()->take(10);
		}
// 			$transaction=Transaction::where('type','admin')->orderBy('id','DESC')->paginate(5);
		return view('backEnd.agent.report')->with('agent',@$agent)->with('parcels',@$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount)->with('aid',@$id)->with('dates',@$dates)->with('datee',@$datee);
	}
		public function asingreport(Request $request){
		  //  return 1;
		$dates=$request->startDate;
		$datee=$request->endDate;
		$id=$request->agent;
		$agent=Agent::get();
// 		dd($agent);
		if ($request->agent  && $request->startDate==null && $request->endDate==null) {
// 			$id=$request->agent;
			
			$parcelr =Parcel::where('agentId',$request->agent)->where('status',4)->where('archive',1)->count();
		  $parcelc =Parcel::where('agentId',$request->agent)->where('status',9)->where('archive',1)->count();
		  $parcelre =Parcel::where('agentId',$request->agent)->where('status',8)->where('archive',1)->count();
		  $parcelpa =Parcel::where('agentId',$request->agent)->where('status',1)->where('archive',1)->count();
	  
		  $parcelpictd =Parcel::where('agentId',$request->agent)->where('status',2)->where('archive',1)->count();
		  $parcelinterjit =Parcel::where('agentId',$id)->where('status',3)->where('archive',1)->count();
		  $parcelhold =Parcel::where('agentId',$request->agent)->where('status',5)->where('archive',1)->count();
		  $parcelrrtupa =Parcel::where('agentId',$request->agent)->where('status',6)->where('archive',1)->count();
		  $parcelrrhub =Parcel::where('agentId',$request->agent)->where('status',7)->where('archive',1)->count();
	  
		  $parcelpriceCOD =Parcel::where('agentId',$request->agent)->where('status','!=',9)->where('archive',1)->sum('cod');
	   //dd($parcelprice);
		  $deliveryCharge= $parcelprice =Parcel::where('agentId',$request->agent)->where('archive',1)->sum('deliveryCharge');
	  
		  $codCharge= $parcelprice =Parcel::where('agentId',$request->agent)->where('archive',1)->sum('codCharge');
	  
		  $Collectedamount =Parcel::where('agentId',$request->agent)->where('archive',1)->sum('cod');
	  
		  $parcelcount =Parcel::where('agentId',$request->agent)->where('archive',1)->count();

			$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->where('parcels.agentId',$request->agent)
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
        // $parcels->appends(['agent'=>$request->agent]);
		}
		elseif($request->agent!=NULL  && $request->startDate!=NULL && $request->endDate!=NULL){ 
		    $id=$request->agent;
			
			$parcelr =Parcel::where('agentId',$request->agent)->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelc =Parcel::where('agentId',$request->agent)->where('status',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelre =Parcel::where('agentId',$request->agent)->where('status',8)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelpa =Parcel::where('agentId',$request->agent)->where('status',1)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
	  
		  $parcelpictd =Parcel::where('agentId',$request->agent)->where('status',2)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelinterjit =Parcel::where('agentId',$id)->where('status',3)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelhold =Parcel::where('agentId',$request->agent)->where('status',5)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelrrtupa =Parcel::where('agentId',$request->agent)->where('status',6)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
		  $parcelrrhub =Parcel::where('agentId',$request->agent)->where('status',7)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();
	  
		  $parcelpriceCOD =Parcel::where('agentId',$request->agent)->where('status','!=',9)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
	  // dd($parcelprice);
		  $deliveryCharge= $parcelprice =Parcel::where('agentId',$request->agent)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
	  
		  $codCharge= $parcelprice =Parcel::where('agentId',$request->agent)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
	  
		  $Collectedamount =Parcel::where('agentId',$request->agent)->where('status',4)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
	  
		  $parcelcount =Parcel::where('agentId',$request->agent)->whereBetween('created_at', [$request->startDate, $request->endDate])->where('archive',1)->count();

			$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->where('parcels.agentId',$request->agent)->whereBetween('parcels.created_at', [$request->startDate, $request->endDate])
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
            //  $parcels->appends(['agent'=>$request->agent,'startDate' => $request->startDate ,'endDate' => $request->endDate]);
		}
		else{
		$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
              ->whereDay('parcels.created_at', date('d'))
              ->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get()->take(15);
		}
// 		return 5;
		return view('backEnd.agent.asingreport')->with('agent',@$agent)->with('parcels',@$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount)->with('aid',@$id)->with('dates',@$dates)->with('datee',@$datee);
	}
}
