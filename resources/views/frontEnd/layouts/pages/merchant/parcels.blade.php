@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Parcel')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <form action="" class="filte-form">
            @csrf
            <div class="row">
              <input type="hidden" value="1" name="filter_id">
              <div class="col-sm-2">
                <input type="text" class="form-control" placeholder="Track Id" name="trackId">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="number" class="form-control" placeholder="Phone Number" name="phoneNumber">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
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
            <div class="tab-inner table-responsive">
               <table id="example777" class="table  table-striped">
                 <thead>
                   <tr>
                    <th>Id</th>
                   <th>Tracking ID</th>
                    <th>Invoice <br> Number</th>
                   <th>Date</th>
                   <th>Recipient</th>
                   <th>Phone</th>
                   <th>Status</th>
                   <th>Rider</th>
                   <th>Total</th>
                   <th>Charge</th>
                   <th>Sub Total</th>
                   <th>Payment Status</th>
                   <th>Note</th>
                   <th>More</th>
                 </tr>
                 </thead>
                <tbody>
                    @php 
                    $dCharge = 0;
                    $pCharge = 0;
                    @endphp
             @foreach($allparcel as $key=>$value)
                 <tr>
                   <td>{{$value->id}}</td>
                   <td>{{$value->trackingCode}}</td>
                    <td>{{$value->invoiceNo}}</td>
                   <td>{{$value->created_at}}</td>
                   <td>{{$value->recipientName}}</td>
                   <td>{{$value->recipientPhone}}</td>
                  <td>
                    @php
                      $parcelstatus = App\Models\Parceltype::find($value->status);
                   @endphp
                     {{$parcelstatus->title}}
                    </td>
                     <td>
                         @php
                            $deliverymanInfo = App\Models\Deliveryman::find($value->deliverymanId);
                            $dCharge += $value->deliveryCharge+$value->codCharge;
                            $pCharge += $value->cod-($value->deliveryCharge+$value->codCharge);
                          @endphp
                          @if($value->deliverymanId) {{$deliverymanInfo->name}} @else Not Asign @endif
                     </td>
                    <td> {{$value->cod}}</td>
                    <td> {{(int)$value->deliveryCharge+(int)$value->codCharge}}</td>
                    <td> {{(int)$value->cod-(int)($value->deliveryCharge+$value->codCharge)}}</td>
                    <td>@if($value->merchantpayStatus==NULL) NULL @elseif($value->merchantpayStatus==0) Processing @else Paid @endif</td>
                    <td>
                        @php 
                            $parcelnote = App\Models\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->first();
                        @endphp
                        @if(!empty($parcelnote))
                        {{$parcelnote->note}} <br>
                        <small>
                            {{$parcelnote->cnote}}
                        </small>
                        @endif
                    </td>
                   <td>
                    <li>
                      <a href="{{url('merchant/parcel/in-details/'.$value->id)}}" class="btn btn-info"><i class="fa fa-eye"></i></a>
                      @if($value->status ==1)
                      <a href="{{url('merchant/parcel/edit/'.$value->id)}}" class="btn btn-danger"><i class="fa fa-edit"></i></a>
                      @endif
                    </li>
                              
                      <li>
                      <a class="btn btn-primary" a href="{{url('merchant/parcel/invoice/'.$value->id)}}"  title="Invoice"><i class="fas fa-list"></i></a>
                      </li>
                    
                      @if($value->status==1)
                      <li>
                        <form action="{{url('merchant/parcel/cancel')}}" method="post">
                        @csrf
                        <input type="hidden" name="pid" value="{{$value->id}}">
                        <input type="submit" class="btn btn-sm btn-danger" value="cancel">
                        </form>

                   
                      </li>   
                      @endif
                   </td>
                 </tr>
                 @endforeach
                </tbody>
               </table>
               {{$allparcel->links('pagination::bootstrap-4')}}
             </div>
        </div>
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
               <table id="example66" class="table  table-striped">
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
                   <th>D Charge</th>
                   <th>P Price</th>
                   
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
                    <td>{{$dCharge}}</td>
                    <td>{{$pCharge}}</td>
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