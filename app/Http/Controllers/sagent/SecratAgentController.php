<?php

namespace App\Http\Controllers\sagent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\SecratAgent;
use App\Models\SecretWithdrawal;
use App\Models\Parcel;
use Carbon\Carbon;
use Auth;



class SecratAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalrc=SecretWithdrawal::where('user_id',Auth::user()->id)->sum('amount');
        $payc=SecretWithdrawal::where('user_id',Auth::user()->id)->where('status',1)->where('mWithdrawal',null)->sum('amount');
        $agent= SecratAgent::where('user_id',Auth::user()->id)->get();
        $tmarchant= SecratAgent::where('user_id',Auth::user()->id)->count();
        $tparcel=0;
        $tdeliverd=0;
        $todayparcel=0;
        $todayDelivered=0;
        $totalcommision=0;
        foreach($agent as $ag){
            $tparcel+=Parcel::where('merchantId',$ag->merchant_id)->count();
            $tdeliverd+=Parcel::where('merchantId',$ag->merchant_id)->where('status',4)->count();
          
            $todayparcel+=Parcel::where('merchantId',$ag->merchant_id)->whereDate('created_at', Carbon::today())->count();
            $todayDelivered+=Parcel::where('merchantId',$ag->merchant_id)->where('status',4)->whereDate('created_at', Carbon::today())->count();
        }

       return view('sagent.dashboard')->with('tmarchant',$tmarchant)->with('tparcel',$tparcel)->with('tdeliverd',$tdeliverd)->with('todayparcel',$todayparcel)->with('todayDelivered',$todayDelivered)->with('totalcommision',$totalcommision)->with('totalrc',$totalrc)->with('payc',$payc);
    }


    public function marchant(){
        $agent= SecratAgent::where('user_id',Auth::user()->id)->get();
        return view('sagent.merchant')->with('agent',$agent);
    }


    public function withdrawal_request(Request $request){
        $withd= new SecretWithdrawal;
        $withd->user_id=Auth::user()->id;
        $withd->merchant_id=$request->mid;
        $withd->amount=$request->amount;
        $withd->pay_method=$request->pay_method;
        $withd->number=$request->number;
        $withd->save();
        Toastr::success('message', 'Withdrawal Request has been send successfully!');
      
        return redirect()->back();

    }

    public function withdrawal(){
        $withd= SecretWithdrawal::where('user_id',Auth::user()->id)->where('mWithdrawal',null)->orderBy('id','DESC')->get();
        return view('sagent.withdrawal')->with('withd',$withd);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SecratAgent  $secratAgent
     * @return \Illuminate\Http\Response
     */
    public function show(SecratAgent $secratAgent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SecratAgent  $secratAgent
     * @return \Illuminate\Http\Response
     */
    public function edit(SecratAgent $secratAgent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SecratAgent  $secratAgent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SecratAgent $secratAgent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SecratAgent  $secratAgent
     * @return \Illuminate\Http\Response
     */
    public function destroy(SecratAgent $secratAgent)
    {
        //
    }
}
