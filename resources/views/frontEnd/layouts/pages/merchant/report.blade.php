@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Parcel')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="{{url('merchant/report')}}" method="get" class="">
            @csrf
            <div class="row">
              
             
              <!-- col end -->
              <div class="col-sm-4">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="{{@$to}}">
              </div>
              <!-- col end -->
              <div class="col-sm-4">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="{{@$end}}">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <button type="submit" class="btn btn-success">Submit </button>
              </div>
              <!-- col end -->
            </div>
          </form>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
              <table id="example" class="table  table-striped " width="100%">
                    
                      <thead>
                      <tr>
                      	<th>SL No.</th>
                       <th>InvoiceNo</th>
                        <th>Ricipient</th>
                        <th>Tracking ID</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>COD</th>
                        <th>C. Update</th>
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
                        @foreach($show_data as $key=>$value)
                        <tr>
                          
                          <td>{{$loop->iteration}}</td>
                           <td>{{$value->invoiceNo}}</td>
                          <td>{{$value->recipientName}}</td>
                          <td>{{$value->trackingCode}}</td>
                          <td> <?php $area=App\Models\Deliverycharge::where('id',$value->orderType)->first(); ?> {{@$area->title}} <br>
                               
                               <td > {{$value->recipientAddress}} </td>
                          <td>  {{$value->recipientPhone}}</td>  
                                
                          </td>
                          <td> {{date('F d Y', strtotime($value->created_at))}} {{date('H:i:s:A', strtotime($value->created_at))}}</td>
                          <td>{{$value->cod}}</td>
                          <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
                          <td>{{$value->merchantAmount}}</td>
                          <td>{{$value->merchantPaid}}</td>
                          <td>{{$value->merchantDue}}</td>
                          <td>{{$value->codCharge}}</td>
                          <td>{{$value->deliveryCharge}}</td>
                         <td>@if($value->merchantpayStatus==NULL)Unpaid   @elseif($value->merchantpayStatus==0) Processing @else Paid @endif</td>
                          <td>@php $parceltype = App\Models\Parceltype::find($value->status); @endphp @if($parceltype!=NULL) {{$parceltype->title}} @endif</td>
                          
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                   
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
               <table id="" class="table  table-striped">
                 <thead>
                   <tr>
                    <th>Parcel</th>
                   <th>Delivered</th>
                   <th> Panding</th>
                   <th>Cancelled</th>
                   <th>Returned to Merchant</th>
                   <th>Picked</th>
                   <th>In Transit</th>
                   <th>Hold</th>
                   <th>Return Pending</th>
                   <th>Return To Hub</th>
                   
                   <th>Cod Price</th>
                 
                   
                 </tr>
                 </thead>
                <tbody>
               
                 <tr>
                    <td>
                       {{$parcelcount}}          
                    </td>
                    <td>
                        {{$parcelr}}@if($parcelr)({{round(($parcelr*100)/$parcelcount,2)}}%)
                        @endif
                    </td>
                    <td>{{$parcelpa}}@if($parcelpa)({{round(($parcelpa*100 )/$parcelcount,2)}}%)
                    @endif
                    </td>
                    <td>{{$parcelc}}@if($parcelc)({{round(($parcelc*100)/$parcelcount,2)}}%)
                    @endif</td>
                    <td>{{$parcelre}}@if($parcelre)({{round(($parcelre*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{$parcelpictd}}@if($parcelpictd)({{round(($parcelpictd*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{$parcelinterjit}}@if($parcelinterjit)({{round(($parcelinterjit*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{$parcelhold}}@if($parcelhold)({{round(($parcelhold*100)/$parcelcount,2)}}%)
                    @endif</td>
                    <td>{{$parcelrrtupa}}@if($parcelrrtupa)({{round(($parcelrrtupa*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{$parcelrrhub}}@if($parcelrrhub)({{round(($parcelrrhub*100)/$parcelcount,2)}}%)
                    @endif</td>
                    
                    <td>{{$parcelprice}}</td>
                  
                   
                 </tr>
           
                </tbody>
               </table>
             </div>
        </div>
    </div>
    <!-- row end -->
</div>
@endsection