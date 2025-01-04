<?php

namespace App\Http\Controllers\Author;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Nearestzone;
use App\Models\Agent;
use App\Models\Parcel;
use App\Models\Deliveryman;
use App\Models\Deliverycharge;
use App\Models\Merchant;

use Auth;
use App\Models\Post;
use App\Models\Parcelnote;
use App\Models\Parceltype;
use DB;
class DashboardController extends Controller
{
    public function index(){
    	return view('backEnd.superadmin.dashboard');
    }
    
    
    	public function hubreport(Request $request){
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
		  $deliveryCharge= Parcel::where('agentId',$request->agent)->where('archive',1)->sum('deliveryCharge');
	  
		  $codCharge= Parcel::where('agentId',$request->agent)->where('archive',1)->sum('codCharge');
	  
		  $Collectedamount =Parcel::where('agentId',$request->agent)->where('archive',1)->sum('cod');
	  
		  $parcelcount =Parcel::where('agentId',$request->agent)->where('archive',1)->count();

			$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->where('parcels.agentId',$request->agent)
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();

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
		  $deliveryCharge= $parcelprice =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
	  
		  $codCharge= $parcelprice =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
	  
		  $Collectedamount =Parcel::where('agentId',$request->agent)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
	  
		  $parcelcount =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

			$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->where('parcels.agentId',$request->agent)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
		}
		else{
		$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get()->take(50);
		}
		
		return view('backEnd.superadmin.report')->with('agent',@$agent)->with('parcels',@$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount)->with('aid',@$id)->with('dates',@$dates)->with('datee',@$datee);
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
		  $deliveryCharge= $parcelprice =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('deliveryCharge');
	  
		  $codCharge= $parcelprice =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('codCharge');
	  
		  $Collectedamount =Parcel::where('agentId',$request->agent)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
	  
		  $parcelcount =Parcel::where('agentId',$request->agent)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

			$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
			->where('parcels.agentId',$request->agent)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
			->where('parcels.archive',1)
            ->orderBy('parcels.id','DESC')
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get();
		}
		else{
		$parcels = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->orderBy('parcels.id','DESC')
            ->where('parcels.archive',1)
            ->select('parcels.*','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
            ->get()->take(50);
		}
		//return 5;
		return view('backEnd.superadmin.reporta')->with('agent',@$agent)->with('parcels',@$parcels)->with('parcelr',@$parcelr)->with('parcelcount',@$parcelcount)->with('parcelc',@$parcelc)->with('parcelpriceCOD',@$parcelpriceCOD)->with('parcelpa',@$parcelpa)->with('parcelre',@$parcelre)->with('id',@$id)->with('parcelpictd',@$parcelpictd)->with('parcelinterjit',@$parcelinterjit)->with('parcelhold',@$parcelhold)->with('parcelrrtupa',@$parcelrrtupa)->with('parcelrrhub',@$parcelrrhub)->with('deliveryCharge',@$deliveryCharge)->with('codCharge',@$codCharge)->with('Collectedamount',@$Collectedamount)->with('aid',@$id)->with('dates',@$dates)->with('datee',@$datee);
	}
	
	
	public function parcelreport(Request $request){
    if($request->mid=='Allmarcent'){
        $id=$request->mid;
      $merchants = Merchant::orderBy('id','DESC')->get();
      $parcelr =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $paid =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantPaid');
    $unpaid =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantDue');
    $parcelpictd =Parcel::where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpriceCOD =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
// dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('deliveryCharge');

    $codCharge= $parcelprice =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('codCharge');

    $Collectedamount =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');

    $parcelcount =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    }else{
    $id=$request->mid;
    $merchants = Merchant::orderBy('id','DESC')->get();
    $parcelr =Parcel::where('merchantId',$request->mid)->where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('merchantId',$request->mid)->where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('merchantId',$request->mid)->where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('merchantId',$request->mid)->where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $paid =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantPaid');
    $unpaid =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantDue');
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

    $parcelcount =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    }

    return view('backEnd.superadmin.reportp')->with('parcelr',$parcelr)->with('paid',$paid)->with('unpaid',$unpaid)->with('parcelcount',$parcelcount)->with('parcelc',$parcelc)->with('parcelpriceCOD',$parcelpriceCOD)->with('parcelpa',$parcelpa)->with('parcelre',$parcelre)->with('merchants',$merchants)->with('id',$id)->with('parcelpictd',$parcelpictd)->with('parcelinterjit',$parcelinterjit)->with('parcelhold',$parcelhold)->with('parcelrrtupa',$parcelrrtupa)->with('parcelrrhub',$parcelrrhub)->with('deliveryCharge',$deliveryCharge)->with('codCharge',$codCharge)->with('Collectedamount',$Collectedamount);
    }
    
    public function report(Request $request){
    if($request->mid=='Allmarcent'){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    $id=$request->mid;
    $merchants = Merchant::orderBy('id','DESC')->get();
    $parcelr =Parcel::where('status',4)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelc =Parcel::where('status',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelre =Parcel::where('status',8)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelpa =Parcel::where('status',1)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpictd =Parcel::where('status',2)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelinterjit =Parcel::where('status',3)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelhold =Parcel::where('status',5)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrtupa =Parcel::where('status',6)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    $parcelrrhub =Parcel::where('status',7)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    $parcelpriceCOD =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');
    $paid =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantPaid');
    $unpaid =Parcel::where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantDue');
// dd($parcelprice);
    $deliveryCharge= $parcelprice =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('deliveryCharge');

    $codCharge= $parcelprice =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('status','!=',9)->where('archive',1)->sum('codCharge');

    $Collectedamount =Parcel::where('status',4)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('cod');

    $parcelcount =Parcel::whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();

    }else{
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
      
    $id=$request->mid;
     $paid =Parcel::where('merchantId',$request->mid)->where('status',4)->where('status','!=',9)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->sum('merchantPaid');
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

    $parcelcount =Parcel::where('merchantId',$request->mid)->whereBetween('present_date', [$request->startDate, $request->endDate])->where('archive',1)->count();
    }

    return view('backEnd.superadmin.marcentreport')->with('show_data',$show_data)->with('paid',$paid)->with('unpaid',$unpaid)->with('parcelr',$parcelr)->with('parcelcount',$parcelcount)->with('parcelc',$parcelc)->with('parcelpriceCOD',$parcelpriceCOD)->with('parcelpa',$parcelpa)->with('parcelre',$parcelre)->with('merchants',$merchants)->with('id',$id)->with('parcelpictd',$parcelpictd)->with('parcelinterjit',$parcelinterjit)->with('parcelhold',$parcelhold)->with('parcelrrtupa',$parcelrrtupa)->with('parcelrrhub',$parcelrrhub)->with('deliveryCharge',$deliveryCharge)->with('codCharge',$codCharge)->with('Collectedamount',$Collectedamount);
    }
}
