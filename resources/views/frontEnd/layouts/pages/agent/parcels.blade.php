@extends('frontEnd.layouts.pages.agent.agentmaster')
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
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="{{$dates}}">
              </div>
              <!-- col end -->
              <div class="col-sm-2">
                <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="{{$datee}}">
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
               
               <table id="exampled" class="table  table-striped table-responsive">
                 <thead>
                     <tr>
                       <th>Sl ID</th>
                       <th>Tracking ID</th>
                       <th>Invoice No </th>
                       <th>Date</th>
                       <th>Shop Name</th>
                       <th>Recipient</th>
                       <th>Phone</th>
                       <th>Address</th>
                       <th>Delivery Man</th>
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
                  @php
                    $deliverymanInfo = App\Models\Deliveryman::find($value->deliverymanId);
                    $merchantInfo = App\Models\Merchant::find($value->merchantId);
                  @endphp
                   <td>{{@$loop->iteration}}</td>
                   <td>{{$value->trackingCode}}</td>
                   <td>{{$value->invoiceNo}}</td>
                   <td>{{$value->created_at}}</td>
                   
                   <td>{{$value->companyName}}</td>
                    <td>{{$value->recipientName}}</td>
                   <td>{{$value->recipientPhone}}</td>
                    <td>{{$value->recipientAddress}}</td>
                   <td>
                     @if($value->deliverymanId) {{$deliverymanInfo->name}}  <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#asignModal{{$value->id}}">Change Asign</button> @else <button class="btn btn-primary" data-toggle="modal" data-target="#asignModal{{$value->id}}">Asign</button> @endif
                     <div id="asignModal{{$value->id}}" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Delivery Man Asign</h5>
                            </div>
                            <div class="modal-body">
                              <form action="{{url('agent/deliveryman/asign')}}" method="POST">
                                @csrf
                                <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                <input type="hidden" name="merchant_phone" value="{{$merchantInfo->phoneNumber}}">
                                <div class="form-group">
                                  <select name="deliverymanId" class="form-control" id="">
                                    <option value="">Select..</option>
                                    @foreach($daliveryman1 as $key=>$deliveryman)
                                    <option value="{{$deliveryman->id}}">{{$deliveryman->name}}</option>
                                    @endforeach
                                  </select>
                                </div>
                                <!-- form group end -->
                                <div class="form-group mrt-15">
                                  <textarea name="note" class="form-control" cols="30" placeholder="Note" ></textarea>
                                </div>
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
                   </td>
                   <td>
                     @php
                        $parcelstatus = App\Models\Parceltype::find($value->status);
                     @endphp
                     {{$parcelstatus->title}} <br>
                     @if($value->agentAprove==null)
                    <span class="btn btn-sm btn-dark "><a href="{{url('agent/parcel/accept/'.$value->id)}}">Parcel not accepted</a></span> 
                    @else
                    <span class="btn btn-sm btn-danger">Parcel accepted</span>
                    @endif
                   </td>
                   <td> {{$value->cod}}</td>
                    <td><?php if ($value->partial_pay==null) {
                            $partial=0;
                          } else {
                            $partial=$value->cod-$value->partial_pay;
                          }
                           ?> {{$value->partial_pay}}</td>
                   <td> {{(int)$value->deliveryCharge+(int)$value->codCharge}}</td>
                   <td> {{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}}</td>
                   <td>@if($value->merchantpayStatus==NULL) NULL @elseif($value->merchantpayStatus==0) Processing @else Paid @endif</td>
                  <td>
                    @php 
                        $parcelnote = App\Models\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->first();
                    @endphp
                    @if(!empty($parcelnote))
                    {{$parcelnote->note}}
                    @endif
                 </td>
                   <td> 
                   @if($value->agentAprove==1)
                   <li>
                       <button class="btn btn-info" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></button>
                   </li>
                          <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                      <div class="modal-dialog  modal-lg">
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
                                                  <td>Pickup Location</td>
                                                  @if($value->pickuploaction==null)
                                                  <td>{{@$value->pickLocation}}</td>
                                                  @else
                                                  <td>{{@$value->pickuploaction}}</td>
                                                  @endif
                                                </tr>
                                                  <td>Recipient Name</td>
                                                  <td>{{$value->recipientName}}</td>
                                                </tr>
                                                <tr>
                                                  <td>Recipient Address</td>
                                                  <td>{{$value->recipientAddress}}</td>
                                                </tr>
                                                 <tr>
                                                  <td>Weight</td>
                                                  <td>{{$value->productWeight}}</td>
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
                                                <tr>
			                                  		<td>Last Update</td>
			                                  		<td>{{date('F d, Y', strtotime($value->updated_at))}} {{date('H:i:s:A', strtotime($value->updated_time))}}</td>
			                                  	</tr>
                                              </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4>PARCEL Note</h4>
                                                    <?php $pnote=App\Models\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->get(); ?>
                                                    <table>
                                                        <tr>
                                                           
                                                             <th>Date</th>
                                                              <th>User </th>
                                                               <th>Note</th>
                                                        </tr>
                                                   
                                                     @foreach($pnote as $pn)
                                                    <tr>
                                                      
                                                        <td>{{ date('F d, Y', strtotime($pn->updated_at))}} {{date('H:i:s:A', strtotime($pn->updated_at))}}</td>
                                                        <td>{{$pn->user}}</td>
                                                        <td>
                                                            {{$pn->note}} <br>
                                                            <small>{{$pn->cnote}}</small>
                                                        
                                                        </td>
                                                    </tr>
                                                      @endforeach
                                                   
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
                    <li>
                        
                       @if($value->status==7 && $value->hubaprove==0)
                         <button class="btn btn-primary" title="Parcel Accepeted Or Rejected" data-toggle="modal" data-target="#pAccOrRej{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
                      @else
                      <button class="btn btn-danger" title="Action" data-toggle="modal" data-target="#sUpdateModal{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
                      @endif
                        </li>
                          <!-- Modal -->
                    
                    <!--rtn accept or reject start-->
                       <div id="pAccOrRej{{$value->id}}" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Parcel Accepted Or Rejected</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="{{url('agent/parcel/acceptReturn')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                        <div class="form-group">
                                            <select name="hubaprove"  class="form-control" id="">
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
                    <!--rtn accept or reject end-->
                    <!-- Modal -->
                      <div id="sUpdateModal{{$value->id}}" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Parcel Status Update</h5>
                            </div>
                            <div class="modal-body">
                              <form action="{{url('agent/parcel/status-update2')}}" method="POST">
                                @csrf
                                <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                <input type="hidden" name="customer_phone" value="{{$value->recipientPhone}}">
                                <div class="form-group">
                                    <select name="status"  onchange="percelDelivery(this)" class="form-control" id="">
                                        @foreach($aparceltypes as $key=>$ptvalue)
                                          <option value="{{$ptvalue->id}}"@if($value->status==$ptvalue->id) selected="selected" @endif @if($value->status ==4 || $value->status==9) disabled @endif>{{$ptvalue->title}}</option>
                                          @endforeach
                                  </select>
                                </div>                                    
                                <!-- form group end -->
                                <div class="form-group mrt-15">
                                                                                <select name="snote" id=""
                                                                                    class="form-control">
                                                                                    <option value="">Select Note
                                                                                    </option>
                                                                                    <option value="Parcel Pending">
                                                                                        Parcel Pending</option>
                                                                                    <option value="Successfully Picked">
                                                                                        Successfully Picked</option>
                                                                                    <option value="Parcel in Transit">
                                                                                        Parcel in Transit</option>
                                                                                    <option
                                                                                        value="Successfully Delivered">
                                                                                        Successfully Delivered</option>
                                                                                    <option value="Parcel in Hold">
                                                                                        Parcel in Hold</option>
                                                                                    <option
                                                                                        value="Parcel Return Pending">
                                                                                        Parcel Return Pending</option>
                                                                                    <option
                                                                                        value="Parcel returned to Hub">
                                                                                        Parcel returned to Hub</option>
                                                                                    <option
                                                                                        value="Parcel returned to Merchant">
                                                                                        Parcel returned to Merchant
                                                                                    </option>
                                                                                    <option value="Parcel Cancelled">
                                                                                        Parcel Cancelled</option>
                                                                                    <option
                                                                                        value="Customer Refused  This Order! ">
                                                                                        Customer Refused This Order!
                                                                                    </option>
                                                                                    <option
                                                                                        value="The customer couldn't receive this Parcel today.">
                                                                                        The customer couldn't receive
                                                                                        this Parcel today.</option>
                                                                                    <option
                                                                                        value="The Customer will take this parcel tomorrow.">
                                                                                        The Customer will take this
                                                                                        parcel tomorrow.</option>
                                                                                    <option
                                                                                        value="Returned from customer and hold in the hub!">
                                                                                        Returned from customer and hold
                                                                                        in the hub!</option>
                                                                                    <option
                                                                                        value="The customer Didn't receive the call!">
                                                                                        The customer Didn't receive the
                                                                                        call!</option>
                                                                                    <option value="Sent to area HUB.">
                                                                                        Sent to area HUB.</option>
                                                                                    <option
                                                                                        value="On the way to deliver!">
                                                                                        On the way to deliver!</option>
                                                                                    <option
                                                                                        value="Wrong Product! return Pending!">
                                                                                        Wrong Product! return Pending!
                                                                                    </option>
                                                                                    <option
                                                                                        value="This Parcel will be delivered tomorrow!">
                                                                                        This Parcel will be delivered
                                                                                        tomorrow!</option>


                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mrt-15">

                                                                                <p id="">Customize Note</p>

                                                                                <div id="myDIV">
                                                                                    <textarea name="note" id="note"
                                                                                        class="form-control" cols="30"
                                                                                        placeholder="Note"></textarea>
                                                                                </div>

                                                                            </div>
                                                                           
                                 <!-- form group end -->
                                <div class="form-group">
                                  <div id="customerpaid" style="display: none;">
                                      <input type="text" class="form-control" value="{{old('customerpay')}}" id="customerpay" name="customerpay"  placeholder="customer pay" /><br />
                                  </div>
                                </div>
                                <!-- form group end -->
                                <div class="form-group">
                                  <button type="submit" class="btn btn-success">Update</button>
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
                      @if($value->status >= 2) 
                      <li><a class="btn btn-primary" a href="{{url('agent/parcel/invoice/'.$value->id)}}"  title="Invoice"><i class="fas fa-list"></i></a></li>
                      @endif
                     @endif
                  </td>
                 </tr>
                 @endforeach
                 </tbody>
               </table>
               
             </div>
              {{$allparcel->links()}}
        </div>
         {{--<div class="col-lg-12 col-md-12 col-sm-12">
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
                  
                   
                 </tr>
           
                </tbody>
               </table>
             </div>
        </div>--}}
    </div>
    <!-- row end -->
</div>

@endsection
