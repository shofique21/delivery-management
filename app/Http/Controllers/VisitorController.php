<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Merchant;
use App\Models\Post;
use App\Models\Parcel;
use App\Models\Parcelnote;
use App\Models\Test;
use App\Models\Contact;
use DB;
use Mail;
use Session;
class visitorController extends Controller
{
    
     public function lafzApi(Request $request){
         if($request->trackId!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.merchantId',$request->merchantId)
        ->where('parcels.trackingCode',$request->trackId)
        ->select('parcels.*','parceltypes.title as parcelStatus','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->first();
         $parcelStatusinfo = Parcelnote::where('parcelId',@$allparcel->id)->orderBy('id','DESC')->get();
       }elseif($request->phoneNumber!=NULL){
   
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.merchantId',$request->merchantId)
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->select('parcels.*','parceltypes.title as parcelStatus','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->first();
        $parcelStatusinfo = Parcelnote::where('parcelId',@$allparcel->id)->orderBy('id','DESC')->get();
       }
       elseif($request->orderNumber!=NULL){
   
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->where('parcels.merchantId',$request->merchantId)
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.invoiceNo',$request->orderNumber)
        ->select('parcels.*','parceltypes.title as parcelStatus','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->first();
        $parcelStatusinfo = Parcelnote::where('parcelId',@$allparcel->id)->orderBy('id','DESC')->get();
       }
        
       elseif($request->startDate!=NULL && $request->endDate!=NULL){
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.merchantId',$request->merchantId)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','parceltypes.title as parcelStatus','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
        $parcelStatusinfo=[];
       }elseif($request->trackId!=NULL || $request->phoneNumber!=NULL && $request->startDate!=NULL && $request->endDate!=NULL){
       $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
       ->where('parcels.merchantId',$request->merchantId)
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.recipientPhone',$request->phoneNumber)
        ->whereBetween('parcels.created_at',[$request->startDate, $request->endDate])
        ->select('parcels.*','parceltypes.title as parcelStatus','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
        $parcelStatusinfo=[];
       }else{
    
        $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->where('parcels.merchantId',$request->merchantId)
         ->select('parcels.*','parceltypes.title as parcelStatus','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
        $parcelStatusinfo=[];
       }
        // return response()->json([
        //   'result' => $allparcel,
        //   ]);
      return response()->json(["status" => "success", "code" => 200
      , "data" => compact('allparcel','parcelStatusinfo')
      ]);
    }
    public function api(){
      $allparcel = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('parceltypes', 'parceltypes.id','=','parcels.status')
        ->whereIn('parcels.merchantId',[24, 230])
         ->select('parcels.*','parceltypes.title','merchants.firstName','merchants.lastName','merchants.phoneNumber','merchants.emailAddress','merchants.companyName','merchants.status as mstatus','merchants.id as mid')
        ->orderBy('id','DESC')
        ->get();
        return response()->json(["status" => "success", "code" => 200
       , "data" => compact('allparcel')
      ]);
        // Test::get();
    }
    public function visitorcontact(Request $request){
      $this->validate($request, [
         'contact_email'=>'required',
         'contact_text'=>'required',
        ]);
      $data = array(
         'contact_title' => $request->contact_title,
         'contact_email' => $request->contact_email,
         'contact_text' => $request->contact_text,
        );
        $send = Mail::send('frontEnd.emails.contact', $data, function($textmsg) use ($data){
         $textmsg->from($data['contact_email']);
         $textmsg->to('info@flingex.com');
         $textmsg->subject($data['contact_text']);
        });


        if($send){
          Toastr::success('message', 'Message sent successfully!');
          return redirect('/contact-us');
        }else{
          Toastr::success('message', 'Message sent successfully!');
          return redirect('/contact-us');
        }
    }
    public function test(){
        $percel=Parcel::all();
        
        foreach($percel as $p){
        $test= new Test;
           $test->pid=$p->id;
           $test->cod=$p->merchantAmount;
           $test->save();
            
        }
    }
    public function merchantsupport(Request $request){
      $this->validate($request, [
         'subject'=>'required',
         'description'=>'required',
        ]);
      $findMerchant = Merchant::find(Session::get('merchantId'));
      $data = array(
         'contact_email' => $findMerchant->emailAddress,
         'description' => $request->description,
        );
        $send = Mail::send('frontEnd.emails.support', $data, function($textmsg) use ($data){
         $textmsg->from($data['contact_email']);
         $textmsg->to('info@flingex.com');
         $textmsg->subject($data['description']);
        });


        if($send){
          Toastr::success('message', 'Message sent successfully!');
          return redirect()->back();
        }else{
         Toastr::success('message', 'Message sent successfully!');
           return redirect()->back();
        }
    }
     public function careerapply(Request $request){
      $this->validate($request, [
         'name'=>'required',
         'email'=>'required',
         'address'=>'required',
         'phone'=>'required',
         'subject'=>'required',
         'cv'=>'required',
        ]);
        $data = array(
         'name' => $request->name,
         'email' => $request->email,
         'address' => $request->address,
         'phone' => $request->phone,
         'subject' => $request->subject,
         'cv' => $request->cv,
        );
      // return $data;
         
         $send = Mail::send('frontEnd.emails.career', $data, function($textmsg) use ($data){
         $textmsg->from($data['email']);
         $textmsg->to('info@flingex.com');
         $textmsg->subject($data['subject']);
         
         $textmsg->attach($data['cv']->getRealPath(), array(
             'as'=> 'cv.'. $data['cv']->getClientOriginalExtension(),
             'mime' => $data['cv']->getMimeType())
         
         );
        });
        
        if($send){
          Toastr::success('message', 'Apply successfully!');
          return redirect()->back();
        }else{
          Toastr::success('message', 'Apply successfully!');
          return redirect()->back();
        }
        
    }
    
    public function contact_add(Request $request){
        // dd($request);
        $contact= new Contact;
        dd($contact);
        $contact->name= $request->name;
        $contact->email= $request->email;
        $contact->subject= $request->subject;
        $contact->massege= $request->message;
        $contact->save();
        return redirect()->back();
        
    }
    
    // track api
    public function parceltrackapi(Request $request){
        $trackparcel = DB::table('parcels')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
         ->where('parcels.trackingCode',$request->trackparcel)
         ->select('parcels.*','nearestzones.zonename')
         ->first();
   
            $trackInformation = Parcelnote::where('parcelId',@$trackparcel->id)->orderBy('id','ASC')->get();
            
            return response()->json(["status" => "success", "code" => 200
            , "data" => compact('trackparcel','trackInformation')
          ]);
    }
}