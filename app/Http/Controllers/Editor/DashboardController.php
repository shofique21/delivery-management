<?php

namespace App\Http\Controllers\editor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\Codcharge;
use App\Models\Deliveryman;
use App\Models\Deliverycharge;
use App\Models\Nearestzone;
use App\Imports\AdminParcelimport;
use App\Models\Merchant;
use App\Models\Discount;
use DB;
use Auth;
use DataTables;
use App\Models\Post;
use App\Models\Parcelnote;
use App\Models\Parceltype;
use App\Exports\ParcelExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index(){
    	return view('backEnd.superadmin.dashboard');
    }
    
     public function merchantduereport(){
          $merchants = DB::table('parcels')
            ->join('merchants', 'merchants.id','=','parcels.merchantId')
            ->where('parcels.merchantpayStatus',null)
            ->where('parcels.archive',1)
            ->where('parcels.status',4)
            ->orderBy('parcels.id','DESC')
            ->select('merchants.id','merchants.companyName')
            ->groupBy(DB::raw('parcels.merchantId'))
            ->get();
    	return view('backEnd.superadmin.merchandue')->with('merchant',$merchants);
    }
    
    public function bulkinvoice(Request $request){
        $parcel=$request->parcel_id;
        
        // dd($parcel);
    return view('backEnd.parcel.bulkinvoice',compact('parcel'));
}
public function export() 
    {
        return \Excel::download(new ParcelExport, 'parcels.xlsx');
    }

public function reportForERP(Request $request){
    // $startDate = $request->startDate;
    // $endDate = $request->endDate;
    $currentDate = date("Y-m-d");
    // $show_data = DB::select("SELECT 'parcels.merchantId,merchants.companyName,parcels.trackingCode,parcels.cod,parcels.deliveryCharge,(parcels.cod+parcels.deliveryCharge)' FROM parcels inner JOIN merchants on 'parcels.merchantId' = 'merchants.id' WHERE 'parcels.created_at' BETWEEN '$startDate' AND '$endDate'");
if($request->status==2){
    if($request->startDate != null && $request->endDate != null){
  if($request->type=='all'){
      $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    // ->where('merchants.merchant_type','=',$request->type)
    ->whereBetween('parcels.picked_date', [$request->startDate, $request->endDate])
    ->orderBy('parcels.id','DESC')
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.picked_date as dates')
      ->get();
      
  }else{  $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    ->where('merchants.merchant_type','=',$request->type)
    ->whereBetween('parcels.picked_date', [$request->startDate, $request->endDate])
    ->orderBy('parcels.id','DESC')
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.picked_date as dates')
      ->get();
}
}}
elseif($request->status==4){
    if($request->type=='all') {
        $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    // ->where('parcelnotes.parcelStatus','=','Picked')
    // ->where('merchants.merchant_type','=',$request->type)
    ->whereBetween('parcels.delivered_at', [$request->startDate, $request->endDate])
    ->orderBy('parcels.id','DESC')
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.delivered_at as dates')
      ->get();
    }else{$show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    // ->where('parcelnotes.parcelStatus','=','Picked')
    ->where('merchants.merchant_type','=',$request->type)
    ->whereBetween('parcels.delivered_at', [$request->startDate, $request->endDate])
    ->orderBy('parcels.id','DESC')
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.delivered_at as dates')
      ->get();}
}

elseif($request->status==8){
    if($request->type=='all') {
        $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    // ->where('parcelnotes.parcelStatus','=','Picked')
    // ->where('merchants.merchant_type','=',$request->type)
    ->whereBetween('parcels.returnmerchant_date', [$request->startDate, $request->endDate])
    ->orderBy('parcels.id','DESC')
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.returnmerchant_date as dates')
      ->get();
        
    }else{ $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    // ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    // ->where('parcelnotes.parcelStatus','=','Picked')
    ->where('merchants.merchant_type','=',$request->type)
    ->whereBetween('parcels.returnmerchant_date', [$request->startDate, $request->endDate])
    ->orderBy('parcels.id','DESC')
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.returnmerchant_date as dates')
      ->get();}
}
else{
    $currentDate = date("Y-m-d");
    $show_data = DB::table('parcels')
    ->join('merchants', 'merchants.id','=','parcels.merchantId')
    //  ->join('parcelnotes','parcelnotes.parcelId','=','parcels.id')
    ->where('parcels.picked_date',  $currentDate)
    ->select('parcels.merchantId','merchants.companyName','parcels.invoiceNo','parcels.trackingCode','parcels.cod','parcels.deliveryCharge','parcels.update_by','parcels.picked_date as dates')
      ->get();
}
//   dd($show_data);
    return view('backEnd.superadmin.reporterp',compact('show_data'));

}
public function typeprepaid(Request $request){
    $start=$request->startDate;
    $end=$request->endDate;
    $merchants = Merchant::orderBy('id','DESC')->where('merchant_type','1')->get();
    if($request->mid=='Allmarcent'){
         $show_data = DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->leftJoin('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->where('merchants.merchant_type','1')
          ->orderBy('id','DESC')
          
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.id as mid','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    //   dd($show_data);
        $id=$request->mid;
         return view('backEnd.addparcel.typemercahnt1')->with('show_data',$show_data)->with('merchants',$merchants)->with('start',$start)->with('end',$end);
      
    
    }else{
        $show_data = DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->leftJoin('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
           ->where('merchants.merchant_type','1')
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.id as mid','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get()->take(50);
      
    $id=$request->mid;
      $paid=0;
      if($request->startDate && $request->endDate){
        $show_data = DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->leftJoin('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
           ->where('merchants.merchant_type','1')
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.id as mid','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
     
      }
    }

    return view('backEnd.addparcel.typemercahnt')->with('show_data',$show_data)->with('merchants',$merchants)->with('start',$start)->with('end',$end);
    }
public function postpaid (Request $request){
    $start=$request->startDate;
    $end=$request->endDate;
    $merchants = Merchant::orderBy('id','DESC')->where('merchant_type','2')->get();
    if($request->mid=='Allmarcent'){
         $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
          ->where('merchants.merchant_type','2')
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.firstName','merchants.id as mid','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
    //   dd($show_data);
        $id=$request->mid;
      
    
    }else{
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
           ->where('merchants.merchant_type','2')
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.id as mid','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get()->take(50);
      
    $id=$request->mid;
      $paid=0;
      if($request->startDate && $request->endDate){
        $show_data = DB::table('parcels')
          ->join('merchants', 'merchants.id','=','parcels.merchantId')
          ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
          ->where('parcels.merchantId',$request->mid)->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->where('parcels.archive',1)
           ->where('merchants.merchant_type','2')
          ->orderBy('id','DESC')
          ->select('parcels.*','nearestzones.zonename','merchants.id as mid','merchants.firstName','merchants.lastName','merchants.pickLocation','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
      ->get();
     
      }
    }

    return view('backEnd.addparcel.postpaid')->with('show_data',$show_data)->with('merchants',$merchants)->with('start',$start)->with('end',$end);
    }
    
    // today collection report
public function collectionReport(Request $request){
    if($request->startDate && $request->endDate){
        $data = [
            'tcount' => DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->count(),
            'tamount'       => DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
          ->sum('cod'),
            'tprecount'    => DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
          ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
           ->where('merchants.merchant_type','1')
          ->count(),
            'tpresum'    =>DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
          ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
           ->where('merchants.merchant_type','1')
          ->sum('cod'),
          'tproscount'=>DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
           ->where('merchants.merchant_type','2')
          ->count(),
          'tprossum'=>DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->whereBetween('parcels.present_date', [$request->startDate, $request->endDate])
           ->where('merchants.merchant_type','2')
          ->sum('cod')
        ];
    
    }else{
        
         $data = [
            'tcount' => DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
          ->where('parcels.present_date',date("Y-m-d"))
          ->count(),
            'tamount'       => DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->where('parcels.present_date',date("Y-m-d"))
          ->sum('cod'),
            'tprecount'    => DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->where('merchants.merchant_type','1')
            ->where('parcels.present_date',date("Y-m-d"))
          ->count(),
            'tpresum'    =>DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->where('merchants.merchant_type','1')
            ->where('parcels.present_date',date("Y-m-d"))
          ->sum('cod'),
          'tproscount'=>DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->where('parcels.present_date',date("Y-m-d"))
           ->where('merchants.merchant_type','2')
          ->count(),
          'tprossum'=>DB::table('parcels')
          ->leftJoin('merchants', 'merchants.id','=','parcels.merchantId')
          ->where('parcels.archive',1)
          ->where('parcels.status',4)
           ->where('parcels.present_date',date("Y-m-d"))
           ->where('merchants.merchant_type','2')
          ->sum('cod')
        ];
    }
    return view('backEnd.superadmin.collection',$data);
}
    
  public function getCustomFilter()
    {
        return view('backEnd.parcel.filter');
    }   
public function getCustomFilterData(Request $request){
    if (request()->ajax()) {
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
                ->get() ;
            
            
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
    
}

}
