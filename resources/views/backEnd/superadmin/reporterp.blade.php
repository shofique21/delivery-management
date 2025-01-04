@extends('backEnd.layouts.master')
@section('title','Report Parcel')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="{{url('editor/reportForERP')}}" method="get" class="">
            @csrf
            <div class="row">
              <!-- col end -->
                <div class="col-sm-3">
               <select class="form-control" name="status">
  <option value="2">Picked</option>
  <option value="4">Delivered</option>
  <option value="8">Return to Merchant</option>
</select>
              </div>
              <div class="col-sm-3">
               <select class="form-control" name="type">
                   <option value="all">All</option>
  <option value="1">Prepaid</option>
  <option value="2">Postpaid</option>
  
</select>
              </div>
              <div class="col-sm-3">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="{{ old('startDate') }}">
              </div>
              <!-- col end -->
              <div class="col-sm-3">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="{{ old('endDate') }}">
              </div>
              <!-- col end -->
              <div class="col-sm-3">
                <button type="submit" class="btn btn-success">Submit </button>
              </div>
              <!-- col end -->
            </div>
          </form>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
            <table id="exampled" class="table  table-striped">
                 <thead>
                   <tr>
                    <th>Merchant ID</th>
                   <th>Company Name</th>
                   <th>Updated By</th>
                   <th> Tracking ID</th>                   
                   <th>Cod Price</th>
                   <th>Delivery Charge</th>
                   <th>Total Amount</th>
                    <th>Date</th>
                             
                   </tr>
                 </thead>
                <tbody>
                    @php $dCharge = 0; $cod=0; $codWithOutdCharge=0; @endphp
                    @foreach ($show_data as $row)
                 <tr>
               
                     <td>{{$row->merchantId}}</td>
                     <td>{{$row->companyName}}</td>
                     <td>{{$row->update_by}}</td>
                     <td>{{$row->trackingCode}}</td>
                     <td>@php $cod += $row->cod; @endphp {{$row->cod}}</td>
                     <td>@php $dCharge += $row->deliveryCharge; $codWithOutdCharge += $row->cod - $row->deliveryCharge @endphp {{$row->deliveryCharge}}</td>
                     <td>{{$row->cod - $row->deliveryCharge}}</td>
                      <td>{{$row->dates}}</td>
                      
                 </tr>
                 @endforeach
              
                  
           
                </tbody>
                <tfoot>
                        <tr>
                      <th colspan="3">&nbsp</th>
                       
                          <th>Total</th>
                          <th>{{$cod}}</th>
                           <th>{{$dCharge}}</th>
                           <th>{{$codWithOutdCharge}}</th>
                            <th colspan="1">&nbsp</th>
                  </tr>
                </tfoot>
               </table>
             </div>
        </div>
    </div>
    <!-- row end -->
</div>
@endsection