@extends('backEnd.layouts.master')
@section('title','Collection report')
@section('content')
<form action="{{url('editor/report-collection')}}" method="get">
@csrf
    <div class="row p-5">
        
        <div class="col-sm-4 ">
            <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="">
        </div>
        <!-- col end -->
        <div class="col-sm-4">
            <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="">
        </div>
        <div class="col-sm-2">
            <input type="submit" class=" form-control" value="submit">
        </div>
    </div>
</form>
<div class="card-body">
   <div class="row">
       <div class="col-md-12 text-center">
           <h5>Today Collection Parcel: {{ $tcount }} </h5>
           <h5>Today Collection Amount: {{round($tamount,2)}}</h5>
       </div>
      </div>
           <div class="row">
               <div class="col-md-6 text-center">
                   <h5>Prepaid Merchant</h5>
                   <P>Today Collection Parcel: {{$tprecount}}</P>
                   <P>Today Collection Amount: {{round($tpresum,2)}}</P>
               </div>
               <div class="col-md-6 text-center">
                    <h5>Postpaid Merchant</h5>
                   <P>Today Collection Parcel: {{$tproscount}}</P>
                   <P>Today Collection Amount: {{round($tprossum,2)}}</P>
               </div>
           </div>
       
   
</div>

@stop