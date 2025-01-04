@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title','Dashboard')
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
                    <div class="col-sm-2 col-12">
                        <button type="submit" class="btn btn-success">Submit </button>
                    </div>
                    <!-- col end -->
                </div>
            </form>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
                <table id="example" class="table  table-striped">
                    <thead>
                        <tr>
                            <th>SL ID</th>
                            <th>Call</th>
                            <th>Tracking ID</th>
                            <th>Date</th>
                            <th>Shop Name</th>
                            <th>Phone </th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Partial</th>
                            <th>Charge</th>
                            <th>Sub Total</th>
                            <th>Payment Status</th>
                            <th>Note</th>
                            <th>More</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allparcel as $key=>$value)
                        <tr>
                            <td>
                                {{$loop->iteration}} <br>
                                
                            </td>
                            <td style="padding: 0"><a href="tel:{{$value->recipientPhone}}" class="p-1 btn-info text-center d-block" style="border-radius: 2px; line-height: 1;">Call Customer</a> <a href="tel:{{$value->phoneNumber}}" class="p-1 btn-success text-center d-block mt-1" style="border-radius: 2px; line-height: 1">Call Merchant</a></td>
                            <td>{{$value->trackingCode}}</td>
                            <td>{{$value->created_at}}</td>
                            <td>{{$value->companyName}}</td>
                            <td>{{$value->recipientPhone}}</td>
                            <td>
                                @php
                                $parcelstatus = App\Models\Parceltype::find($value->status);
                                @endphp
                                {{$parcelstatus->title}}
                            </td>
                            <td> {{$value->cod}}</td>
                            <td><?php if ($value->partial_pay==null) {
                            $partial=0;
                          } else {
                            $partial=$value->cod-$value->partial_pay;
                          }
                           ?> {{$value->partial_pay}}</td>
                            <td> {{$value->deliveryCharge+$value->codCharge}}</td>
                            <td> {{($value->cod-($value->deliveryCharge+$value->codCharge))-$partial}}</td>
                            <td>@if($value->merchantpayStatus==NULL) NULL @elseif($value->merchantpayStatus==0)
                                Processing @else Paid @endif</td>
                            <td>
                                @php
                                $parcelnote =
                                App\Models\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->first();
                                @endphp
                                @if(!empty($parcelnote))
                                {{$parcelnote->note}}<br>
                                <small>
                                    {{$parcelnote->cnote}}
                                </small>
                                @endif
                            </td>
                            <td> <button class="btn btn-info" href="#" data-toggle="modal"
                                    data-target="#merchantParcel{{$value->id}}" title="View"><i
                                        class="fa fa-eye"></i></button>
                                <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Parcel Details</h5>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-bordered">
                                                     <tr>
                                                    <td>Recipient Name</td>
                                                    <td>{{$value->recipientName}}</td>
                        </tr>
                         <tr>
                                                    <td>Recipient Phone</td>
                                                    <td>{{$value->recipientPhone}}</td>
                        </tr>
                        <tr>
                            <td>Recipient Address</td>
                            <td>{{$value->recipientAddress}}</td>
                        </tr>
                                                    <tr>
                                                        <td>Merchant Name</td>
                                                        <td>{{$value->firstName}} {{$value->lastName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Merchant Phone</td>
                                                        <td>{{$value->phoneNumber}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Merchant Email</td>
                                                        <td>{{$value->emailAddress}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Company</td>
                                                        <td>{{$value->companyName}}</td>
                                                    </tr>
                                                   
                        <tr>
                            <td>COD</td>
                            <td>{{$value->cod}}</td>
                        </tr>
                        <tr>
                            <td>C. Charge</td>
                            <td>{{$value->codCharge}}</td>
                        </tr>
                        <tr>
                            <td>D. Charge</td>
                            <td>{{$value->deliveryCharge}}</td>
                        </tr>
                        <tr>
                            <td>Sub Total</td>
                            <td>{{$value->merchantAmount}}</td>
                        </tr>
                        <tr>
                            <td>Paid</td>
                            <td>{{$value->merchantPaid}}</td>
                        </tr>
                        <tr>
                            <td>Due</td>
                            <td>{{$value->merchantDue}}</td>
                        </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@if(Session::get('jobstatus')==2)
@if($value->pmanaprove ==1 || $value->status ==2)
<span class="btn btn-sm btn-success"><i class="fa fa-check" style="font-size:25px"></i></span>
@elseif($value->pmanaprove =='0')
<span class="btn btn-sm btn-danger"><i class="fa fa-times" style="font-size:33px"></i></span>
@else
<button class="btn btn-warning" title="Parcel Accepted Or Rejected" data-toggle="modal" data-target="#pickupAccOrRej{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
@endif
@else
@if($value->dmanaprove =='0')
                                <span class="btn btn-sm btn-warning">Rejected</span>
                       @elseif($value->status !=1)
                       <button class="btn btn-danger" title="Action" data-toggle="modal" data-target="#sUpdateModal{{$value->id}}"><i
                        class="fa fa-sync-alt"></i></button> <!-- Modal -->
                       @else

                        <button class="btn btn-primary" title="Parcel Accepeted Or Rejected" data-toggle="modal" data-target="#dAccOrRej{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
                               @endif
@endif

<div id="pickupAccOrRej{{$value->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pickup Accepted Or Rejected</h5>
            </div>
            <div class="modal-body">
                <form action="{{url('deliveryman/parcel/pickupaccept')}}" method="POST">
                    @csrf
                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                    <div class="form-group">
                        <select name="pmanaprove"  class="form-control" id="">
                          <option value="1">Accept</option>
                          <option value="0">Reject</option>
                                
                        </select>
                    </div>
                    <!-- form group end -->
                    <!-- form group end -->
                    
                
                 
                    <!-- form group end -->
                    <div class="form-group">
                        <button class="btn btn-success">Update</button>
                    </div>
                    <!-- form group end -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->

        
        <div id="dAccOrRej{{$value->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Parcel Accepted Or Rejected</h5>
            </div>
            <div class="modal-body">
                <form action="{{url('deliveryman/parcel/accept')}}" method="POST">
                    @csrf
                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                    <div class="form-group">
                        <select name="dmanaprove"  class="form-control" id="">
                          <option value="1">Accept</option>
                          <option value="0">Reject</option>
                                
                        </select>
                    </div>
                    <!-- form group end -->
                    <!-- form group end -->
                    
                
                 
                    <!-- form group end -->
                    <div class="form-group">
                        <button class="btn btn-success">Update</button>
                    </div>
                    <!-- form group end -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div id="sUpdateModal{{$value->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Parcel Status Update</h5>
            </div>
            <div class="modal-body">
                <form action="{{url('deliveryman/parcel/status-update')}}" method="POST">
                    @csrf
                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                    <input type="hidden" name="customer_phone" value="{{$value->recipientPhone}}">
                    <div class="form-group">
                        <select name="status" onchange="percelDelivery(this)" class="form-control" id="">
                            @foreach($parceltypes as $key=>$ptvalue)
                            @if($key < 3) @continue @endif <option value="{{$ptvalue->id}}" @if($value->
                                status==$ptvalue->id) selected="selected" @endif @if($value->status ==4 ||
                                $value->status==9) disabled @endif>{{$ptvalue->title}}</option>
                                @endforeach
                        </select>
                    </div>
                    <!-- form group end -->
                    <!-- form group end -->
                    
                    <div class="form-group mrt-15">

                        <p id=""> Note</p>

                        <div id="myDIV">
                            <textarea name="note" id="note" class="form-control" cols="30"
                                placeholder="Note"></textarea>
                        </div>

                    </div>
                    <!-- form group end -->
                    <!-- form group end -->
                    <div class="form-group">
                        <div id="customerpaid" style="display: none;">
                            <input type="text" class="form-control" value="{{old('customerpay')}}" id="customerpay"
                                name="customerpay" placeholder="customer pay" /><br />
                        </div>
                    </div>
                    <!-- form group end -->
                    <div class="form-group">
                        <button class="btn btn-success">Update</button>
                    </div>
                    <!-- form group end -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal end -->
@if(Session::get('jobstatus')==1)
@if($value->status >= 2)
<a class="btn btn-primary" href="{{url('deliveryman/parcel/invoice/'.$value->id)}}" title="Invoice"><i
        class="fas fa-list"></i></a>
@endif
@if($value->partial_pay==null)
<li>
    <a class="btn-dark  btn" href="#" title="Invoice" data-toggle="modal" data-target="#partial_pay{{$value->id}}"><i
            class="fa fa-box"></i></a>
</li>
@endif
@endif
<div id="partial_pay{{$value->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Partial Pay </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{url('deliveryman/parcel/partial_pay')}}" method="post">
                            @csrf

                            <label for="">Partial Pay</label>
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input class="form-control" type="text" name="partial_pay">
                            <input class="btn btn-sm btn-dark" type="submit" value="submit" name="">
                        </form>

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
               <table id="example" class="table  table-striped">
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
                   <th>Sub Total</th>
                 
                   
                 </tr>
                 </thead>
                <tbody>
             
                 <tr>
                    <td>
                       {{@$parcelcount}}          
                    </td>
                    <td>
                        {{@$parcelr}}@if($parcelr)({{round(($parcelr*100)/$parcelcount,2)}}%)
                        @endif
                    </td>
                    <td>{{@$parcelpa}}@if($parcelpa)({{round(($parcelpa*100 )/$parcelcount,2)}}%)
                    @endif
                    </td>
                    <td>{{@$parcelc}}@if($parcelc)({{round(($parcelc*100)/$parcelcount,2)}}%)
                    @endif</td>
                    <td>{{@$parcelre}}@if($parcelre)({{round(($parcelre*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{@$parcelpictd}}@if($parcelpictd)({{round(($parcelpictd*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{@$parcelinterjit}}@if($parcelinterjit)({{round(($parcelinterjit*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{@$parcelhold}}@if($parcelhold)({{round(($parcelhold*100)/$parcelcount,2)}}%)
                    @endif</td>
                    <td>{{@$parcelrrtupa}}@if($parcelrrtupa)({{round(($parcelrrtupa*100)/$parcelcount,2)}}%)@endif</td>
                    <td>{{@$parcelrrhub}}@if($parcelrrhub)({{round(($parcelrrhub*100)/$parcelcount,2)}}%)
                    @endif</td>
                    
                    <td>{{@$parcelpriceCOD}}</td>
                    <td>{{@$subtotal}}</td>
                  
                   
                 </tr>
           
                </tbody>
               </table>
             </div>
        </div>
</div>
<!-- row end -->
</div>
@endsection