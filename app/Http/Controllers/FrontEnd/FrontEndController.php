<?php

namespace App\Http\Controllers\FrontEnd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Banner;
use App\Models\Branch;
use App\Models\Price;
use App\Models\Parcel;
use App\Models\Service;
use App\Models\District;
use App\Models\Feature;
use App\Models\Deliverycharge;
use App\Models\Partner;
use App\Models\Parcelnote;
use App\Models\About;
use App\Models\Counter;
use App\Models\Clientfeedback;
use App\Models\Career;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Notice;
use DB;
use Session;
class FrontEndController extends Controller
{
    public function index(){
        $banner = Banner::where('status',1)->orderBy('id','DESC')->get();
        $about = About::where('status',1)->limit(1)->orderBy('id','DESC')->get();
        $services = Service::where('status',1)->orderBy('id','ASC')->get();
        $counter = Counter::where('status',1)->limit(4)->orderBy('id','ASC')->get();
        $prices = Price::where('status',1)->limit(4)->orderBy('id','ASC')->get();
        $features = Feature::where('status',1)->orderBy('id','ASC')->get();
        $clientsfeedback = Clientfeedback::where('status',1)->orderBy('id','DESC')->get();
        //dd($banner);
        return view('frontEnd.index',compact('banner','about','counter','services','prices','features','clientsfeedback'));
    }

    public function login(){
        return view('backEnd.setting.login');
    }
  
    public function costCalculate($cod,$weight){
        // fixed delivery charge
     if($weight > 1){
      $extraweight = $weight-1;
      $deliverycharge = (Session::get('deliverycharge')*1)+($extraweight*Session::get('extradeliverycharge'));
     }else{
      $deliverycharge = (Session::get('deliverycharge'));
     }
     // fixed cod charge
     if($cod > 100){
    //   $extracod=$cod -100;
    //   $extracodcharge = $extracod/100;
      $extracodcharge = 0;
      $codcharge = Session::get('codcharge')+$extracodcharge;
     }else{
      $codcharge= Session::get('codcharge');
     }
     Session::put('codpay',$cod);
     Session::put('pdeliverycharge',$deliverycharge);
     Session::put('pcodecharge',$codcharge);
    return response()->json($deliverycharge);
        
    }
    public function costCalculateResult(){
        return view('frontEnd.layouts.pages.costcalculate');
    }
    public function register(){
        return view('frontEnd.layouts.pages.register');
    }
    public function marchentlogin(){
        if(Session::get('merchantId')){
           return redirect('merchant/dashboard');
        }else{    
          return view('frontEnd.layouts.pages.marchentlogin');
        }
    }
    public function parceltrack(Request $request){
        
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.trackingCode',$request->trackparcel)
         ->select('parcels.*','nearestzones.zonename')
         ->first();
   
            $trackInfos = Parcelnote::where('parcelId',@$trackparcel->id)->orderBy('id','ASC')->get();

             return view('frontEnd.layouts.pages.trackparcel',compact('trackparcel','trackInfos'));
    
        
    }
    public function parceltrackget($id){
         $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.trackingCode',$id)
         ->select('parcels.*','nearestzones.zonename')
         ->orderBy('id','DESC')
         ->first();
         
         
        if($trackparcel){
            $trackInfos = Parcelnote::where('parcelId',$trackparcel->id)->orderBy('id','ASC')->get();
                        // return $trackInfos;
             return view('frontEnd.layouts.pages.trackparcel',compact('trackparcel','trackInfos'));
        }else{
            return redirect()->back();
        }
    }
   
    public function aboutus(){
         $aboutus = About::where('status',1)->limit(1)->orderBy('id','DESC')->get();
        return view('frontEnd.layouts.pages.aboutus',compact('aboutus'));
    }
    public function ourservice($id){
         $servicedetails = Service::where(['id'=>$id,'status'=>1])->first();
         if($servicedetails){
        return view('frontEnd.layouts.pages.service',compact('servicedetails'));
        }else{
           return redirect('404'); 
        }
    }

    public function onetimeservice(){
        return view('frontEnd.layouts.pages.onetimeservice');
    }
    public function career(){
        $careers = Career::where('status',1)->get();
        return view('frontEnd.layouts.pages.careers',compact('careers'));
    }
    public function careerdetails($id,$slug){
         $careerdetails = Career::where(['id'=>$id,'status'=>1])->first();
         if($careerdetails){
        return view('frontEnd.layouts.pages.careerdetails',compact('careerdetails'));
        }else{
           return redirect('404'); 
        }
    }
    public function notice(){
        $notices = Notice::where('status',1)->get();
        return view('frontEnd.layouts.pages.notices',compact('notices'));
    }
    public function noticedetails($id,$slug){
         $noticedetails = Notice::where(['id'=>$id,'status'=>1])->first();
         if($noticedetails){
        return view('frontEnd.layouts.pages.noticedetails',compact('noticedetails'));
        }else{
           return redirect('404'); 
        }
    }
     public function privacypolicy(){
        return view('frontEnd.layouts.pages.privacypolicy');
    }
    public function gallery(){
        $gallery = Gallery::where('status',1)->get();
        return view('frontEnd.layouts.pages.gallery',compact('gallery'));
    }
    
      public function branches(){
    	$brances = DB::table('branches')
    	->join('nearestzones', 'branches.area', '=', 'nearestzones.id' )
    	->select('branches.*', 'nearestzones.zonename')
        ->orderBy('id','DESC')
    	->get();
    	 return view('frontEnd.layouts.pages.brances',compact('brances'));
    }
    
    public function searchBranches(Request $request){

     if($request->ajax())
     {
      $output = '';
      $query = $request->get('query');
      if($query != '')
      {
       $data = DB::table('branches')
                ->where("branches.name","LIKE", "%".$query."%")
                ->join('nearestzones', 'branches.area', '=', 'nearestzones.id' )
    	->select('branches.*', 'nearestzones.zonename')
        ->orderBy('id','DESC')
                ->get();
         
      }
      else
      {
       $data = DB::table('branches')
    	->join('nearestzones', 'branches.area', '=', 'nearestzones.id' )
    	->select('branches.*', 'nearestzones.zonename')
        ->orderBy('id','DESC')
    	->get();
      }
      $total_row = $data->count();
      if($total_row > 0)
      {
       foreach($data as $row)
       {
        $output .= '
      
         <div class="col-md-4">
                    <div class="shadow-sm p-2 mb-4 bg-white rounded agent-card">
                    <img src="'.asset($row->images).'" class="img-responsive" style="max-width: 100%;"/>
                        <h5 class="mt-3">'.$row->name.'</h5>
                        <p>Phone: <a href="tel:'.$row->phone.'">'.$row->phone.'</a><br>Address: '.$row->address.'<br> Area : '.$row->zonename.'</p>
                    </div>
                </div>
        ';
       }
      }
      else
      {
       $output = '
      <div class="col-md-4">No Data Found</div>
       </tr>
       ';
      }
      $data = array(
       'table_data'  => $output,
       'total_data'  => $total_row
      );

      echo json_encode($data);
     }
    }
    
    
    public function contact(){
        return view('frontEnd.layouts.pages.contact');
    }
    public function contact_add(Request $request){
        dd($request);
        $contact= new Contact;
        $contact->name= $request->name;
        $contact->email= $request->email;
        $contact->subject= $request->subject;
        $contact->massege= $request->message;
        $contact->save();
        return back();
        
    }
    
    public function termscondition(){
        return view('frontEnd.layouts.pages.termscondition');
    }
    
     public function marchentdashboard(){
        return view('frontEnd.layouts.pages.marchentdashboard');
    }

   
     
}
