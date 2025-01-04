@extends('backEnd.layouts.master')
@section('title','Merchant type Report')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="{{url('editor/typeprepaid')}}" method="get" class="">
            @csrf
            <div class="row">
              
            <div class="col-sm-6">
              <select name="mid" id=""  class="form-control select2">
            <option value="Allmarcent">All Merchant </option>
              @foreach ($merchants as $mar)
              <option  value="{{$mar->id}}">{{$mar->companyName}} ({{$mar->phoneNumber}})</option>
              @endforeach
              </select>
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="<?php echo date("Y-m-d") ?>">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="<?php echo date("Y-m-d") ?>">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <button type="submit" class="btn btn-success">Submit </button>
              </div>
              <!-- col end -->
            </div>
          </form>
        </div>
        <style>
        @media only screen and (min-width: 992px){
        .merchant-report-c-table.dataTable td, .merchant-report-c-table.dataTable th {
            max-width: 7rem;
            padding: .4rem !important;
        }
        </style>
        <div class="row">
              <table id="exampled" class="table  table-striped merchant-report-c-table" width="100%">
                    <?php
                    $tc=0;
                    $tt=0;
                    $pc=0;
                    $pt=0;
                    $picc=0;
                    $pict=0;
                    $inc=0;
                    $int=0;
                    $dc=0;
                    $dt=0;
                    $hc=0;
                    $ht=0;
                    $rpc=0;
                    $rpt=0;
                    $rhc=0;
                    $rht=0;
                    $rmc=0;
                    $rmt=0;
                    $cc=0;
                    $ct=0;
                    $dcc=0;
                    $paid=0;
                    
                    ?>
                      <thead>
                      <tr>
                      	<th><input type="checkbox"  id="My-Button"></th>
                      	<th>Merchant</th>
                       <th>InvoiceNo</th>
                        <th>Ricipient</th>
                        <th>Tracking ID</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Created</th>
                         <th>Updated</th>
                        <th>COD</th>
                       
                        <th>Subtotal</th>
                        <th>Paid Bills</th>
                        <th>Due Bills</th>
                        <th>C. Charge</th>
                        <th>D. Charge</th>
                        <th>Pay Status</th>
                        <th>Status</th>
                        
                      </tr>
                      </thead>
                      <tbody>
                          

                @foreach($show_data->chunk(3) as $row)
                        @foreach($row as $key=>$value)
                        <tr>
                           <?php
                    $s=$show_data->count();
                    $tc=$show_data->count();
                    $tt=$show_data->sum('cod');
                    $pc=$show_data->where('status',1)->count();
                    $pt=$show_data->where('status',1)->sum('cod');
                    $picc=$show_data->where('status',2)->count();
                    $pict=$show_data->where('status',2)->sum('cod');
                    $inc=$show_data->where('status',3)->count();
                    $int=$show_data->where('status',3)->sum('cod');
                    $dc=$show_data->where('status',4)->count();
                    $dt=$show_data->where('status',4)->sum('cod');
                    $hc=$show_data->where('status',5)->count();
                    $ht=$show_data->where('status',5)->sum('cod');
                    $rpc=$show_data->where('status',6)->count();
                    $rpt=$show_data->where('status',6)->sum('cod');
                    $rhc=$show_data->where('status',7)->count();
                    $rht=$show_data->where('status',7)->sum('cod');
                    $rmc=$show_data->where('status',8)->count();
                    $rmt=$show_data->where('status',8)->sum('cod');
                    $cc=$show_data->where('status',9)->count();
                    $ct=$show_data->where('status',9)->sum('cod');
                    $dcc=$show_data->sum('deliveryCharge');
                    $paid=$show_data->where('merchantpayStatus',1)->where('status',4)->sum('cod');
                    ?>
                           <td>{{$loop->iteration}}</td>
                          <td>{{$value->companyName}}</td>
                           <td>{{$value->invoiceNo}}</td>
                          <td>{{$value->recipientName}}</td>
                          <td>{{$value->trackingCode}}</td>
                          <td>  {{@$value->zonename}} <br>
                               
                               <td > {{$value->recipientAddress}} </td>
                          <td>  {{$value->recipientPhone}}</td>  
                                
                          </td>
                          <td> {{date('F d Y', strtotime($value->created_at))}} </td>
                           <td> {{date('F d Y', strtotime($value->updated_at))}} </td>
                          <td>{{round($value->cod,2)}}</td>
                       
                          <td>{{$value->merchantAmount}}</td>
                          <td>{{$value->merchantPaid}}</td>
                          <td>{{$value->merchantDue}}</td>
                          <td>{{$value->codCharge}}</td>
                          <td>{{$value->deliveryCharge}}</td>
                         <td>@if($value->merchantpayStatus==NULL)Unpaid   @elseif($value->merchantpayStatus==0) Processing @else Paid @endif</td>
                          <td>@php $parceltype = App\Models\Parceltype::find($value->status); @endphp @if($parceltype!=NULL) {{$parceltype->title}} @endif</td>
                         
                        </tr>
                        @endforeach
                         @endforeach
                      </tbody>
                    </table>
                   
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
            <table id="exampled" class="table  table-striped">
                 <thead>
                   <tr>
                    <th>Parcel</th>
                   <th>Panding</th>
                   <th>Picked</th>
                   <th>In Transit</th>
                   <th>Delivered</th>
                   <th>Hold</th>
                   <th>Return Pending</th>
                   <th>Return To Hub</th>
                   <th>Returned to Merchant</th>
                   <th>Cancelled</th>
                   
                   <th>Cod Price</th>
                   <th>Delivery Charge</th>
                   
                   <th>Collected Amount</th>
                   <!--<th>Collected D.Charge</th>-->
                   <th>Paid Amount</th>
                   <th>Unpaid Amount</th>
                 
                   
                 </tr>
                 </thead>
                <tbody>
                
                 <tr>
                    <td>
                       {{$tc}} <br>
                       {{$tt}}
                    </td>
                    <td>
                       {{$pc}} <br>
                       {{$pt}}
                    </td>
                    <td>
                       {{$picc}} <br>
                       {{$pict}}
                    </td>
                    <td>
                       {{$inc}} <br>
                       {{$int}}
                    </td>
                    <td>
                       {{$dc}} <br>
                       {{$dt}}
                    </td>
                    <td>
                       {{$hc}} <br>
                       {{$ht}}
                    </td>
                    <td>
                      
                       {{$rpc}} <br>
                       {{$rpt}}
                    </td>
                    <td>
                       {{$rhc}} <br>
                       {{$rht}}
                    </td>
                    <td>
                       {{$rmc}} <br>
                       {{$rmt}}
                    </td>
                    <td>
                       {{$cc}} <br>
                       {{$ct}}
                    </td>
                      <td>
                       
                       {{$tt}}
                    </td>
                     <td>
                       
                       {{$dcc}}
                    </td>
                     <td>
                       
                       {{$dt}}
                    </td>
                     <td>
                       {{$paid}}
                    </td>
                  <td>
                       {{$dt-$paid}}
                    </td>
                   
                 </tr>
           
                </tbody>
               </table>
             </div>
        </div>
    </div>
    <!-- row end -->
</div>
@endsection