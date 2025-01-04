@extends('backEnd.layouts.master')
@section('title',$parceltype)

@section('content')
<style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible !important;
  }
  #printSection {
    position:absolute !important;
    left:0;
    top:0;
  }
}
</style>
  <!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="box-content">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12  ">
                <div class="card custom-card">
                    <div class="col-sm-12">
                      <div class="manage-button">
                        <div class="body-title">
                          <h5>{{$parceltype}} </h5>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 p-2 bg-success">
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
                            <input type="number" class="form-control" placeholder="Merchant Id" name="merchantId">
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
                            <button type="submit" class="btn btn-dark">Submit </button>
                               <!--<a download="parcel.xlsx" href="#" class="ml-2 btn btn-dark" onclick="return ExcellentExport.convert({ anchor: this, filename: 'parcel', format: 'xlsx'},[{name: 'Sheet Name Here 1', from: {table: 'datatable'}}]);">Export to CSV</a>-->
                          </div>
                          <!--<button type="button" id="export" data-export="export">Export</button>-->

                         
                          <!-- col end -->
                        </div>
                      </form>
                    </div>
                  <div class="card-body col-lg-12 col-md-12 col-sm-12"  id="">
                     <table id="exampled" class="table table-bordered table-striped custom-table table-responsive" width="100%">
                        <thead>
                      <tr>
                        <th>Id</th>
                         <th>User</th>
                        <th>Company Name</th>
                         <th>InvoiceNo</th>
                        <th>Ricipient</th>
                        <th>Tracking ID</th>
                        <th>Area</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Rider</th>
                        <th>Agent</th>
                        <th>C. Update</th>
                        <th>Status</th>
                        <th>Total</th>
                         <th>Partial</th>
                        <th>Charge</th>
                        <th>Sub Total</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_data as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{@$value->user}}</td>
                          @php
                            $merchant = App\Models\Merchant::find($value->merchantId);
                            $agentInfo = App\Models\Agent::find($value->agentId);
                            $deliverymanInfo = App\Models\Deliveryman::find($value->deliverymanId);
                          @endphp
                           <td>{{$merchant->companyName}}</td>
                             <td>{{$value->invoiceNo}}</td>
                          <td>{{$value->recipientName}}</td>
                          <td>{{$value->trackingCode}}</td>
                          <td> <?php $area=App\Models\Deliverycharge::where('id',$value->orderType)->first(); ?> {{@$area->title}} <br>
                               
                               
                                
                          </td>
                          <td > {{$value->recipientAddress}} </td>
                          <td>  {{$value->recipientPhone}}</td>
                         
                          <td>@if($value->deliverymanId) {{$deliverymanInfo->name}} <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#asignModal{{$value->id}}">Change Asign</button> @else <button class="btn btn-primary" data-toggle="modal" data-target="#asignModal{{$value->id}}">Asign</button> @endif</td>
                          <!-- Modal -->
                          <div id="asignModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Deliveryman Asign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/deliveryman/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <input type="hidden" name="merchant_phone" value="{{$merchant->phoneNumber}}">
                                    <div class="form-group">
                                      <select name="deliverymanId" class="form-control" id="">
                                        <option value="">Select</option>
                                        @foreach($deliverymen as $key=>$deliveryman)
                                        <option value="{{$deliveryman->id}}">{{$deliveryman->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <!-- form group end -->
                                    <div class="form-group">
                                      <textarea name="note" class="form-control" ></textarea>
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
                          <td>@if(@$value->agentId) {{@$agentInfo->name}}<button class="btn btn-dark btn-sm" data-toggle="modal" data-target="#agentModal{{$value->id}}">Change Asign</button> @else <button class="btn btn-primary" data-toggle="modal" data-target="#agentModal{{$value->id}}">Asign</button> @endif</td>
                          <!-- Modal -->
                          <div id="agentModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Agent Asign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/agent/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <input type="hidden" name="merchant_phone" value="{{$merchant->phoneNumber}}">
                                    <div class="form-group">
                                      <select name="agentId" class="form-control" id="">
                                        <option value="">Select</option>
                                        @foreach($agents as $key=>$agent)
                                        <option value="{{$agent->id}}">{{$agent->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    
                                    <div class="form-group">
                                      <textarea name="note" class="form-control" ></textarea>
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
                          <td>{{date('F d, Y', strtotime($value->created_at))}} {{date('H:i:s:A', strtotime($value->created_time))}}</td>
                          <td><?php $sptype=App\Models\Parceltype::where('id',$value->status)->first(); ?><span class="btn btn-sm btn-danger">{{$sptype->title}}</span>
                          <small>{{$value->note}} </small>
                          </td>
                          <td>{{$value->cod}} 
                       
                          </td>
                          <td> <?php if ($value->partial_pay==null) {
                            $partial=0;
                          } else {
                            $partial=(int)$value->cod-(int)$value->partial_pay;
                          }
                           ?> {{$value->partial_pay}}</td>
                          <td>{{(int)$value->deliveryCharge+(int)$value->codCharge}}</td>
                          <td>{{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}}</td>
                         
                          <td>
                               @if(Auth::user()->role_id <= 3 )
                            <ul class="action_buttons">
                                <li>
                                      <button class="edit_icon" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></button>
                                      <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-lg" style="width:2000px;">
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
                                                  <td>{{$value->pickLocation}}</td>
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
                                                         <td>{{$pn->note}} <br> <small>
                                                            {{$pn->cnote}}
                                                        </small>
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
                                </li>
                                @if(Auth::user()->role_id <= 2 )
                                 @if(Auth::user()->role_id == 1)
                                 <li><a href="{{url('editor/parcel/restore/'.$value->id)}}" class="edit_icon"><i class="fa fa-people-carry	"></i></a></li>
                                 
                                 @endif
                            
                                
                               
                               
                                 
                              
                                
                                 
                             
                                
                             
  
    
     @endif
                              </ul>
                              @endif
                          </td>
                            
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
          </div>
        </div>
    </div>
  </section>

<!-- Modal Section  -->
@endsection
