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
       <div class="col-lg-12 col-md-12 col-sm-12 p-2 bg-info">
                      <form action="" class="filte-form">
                        @csrf        
        <div class=row >
         <div class="col-md-4 ">
                <select class=" form-control select2" name="merchantId">
                     <option value="">Select One</option>
                    @foreach($merchants as $ma)$ma
                    <option value="{{$ma->id}}">{{$ma->companyName}}</option>
                    @endforeach
                </select>
              
            </div>
            <div class="col-md-2 ">
                <select class=" form-control select2" name="status">
                     <option value="">Select One</option>
                    @foreach($status as $st)
                    <option value="{{$st->id}}">{{$st->title}}</option>
                    @endforeach
                </select>
                
            </div>
            <!--<div class="col-md-2 ">-->
            <!--    <select class=" form-control">-->
            <!--        <option value="">Select One</option>-->
            <!--        <option value="1">Paid</option>-->
            <!--        <option value="null">Unpaid</option>-->
            <!--    </select>-->
                
            <!--</div>-->
            <div class="col-md-2 ">
                
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
            </div>
            <div class="col-md-2">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="endDate">
            </div>
            <div class="col-md-2">
                
                <input type="submit" class="btn btn-danger" value="Submit">
                
            </div>
            
        </div>
         </form>
          </div>
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
                    
                  <div class="card-body"  id="">
                  <form action="{{url('editor/parcel/archive-create')}}" method="get" id="myform">   
                    @csrf 
                  <table id="" class="table table-hover table-bordered ">
                      	@if(Auth::user()->role_id <= 2)
		                <button type="submit" class="bulkbutton" onclick="return confirm('Are you want change this?')">archive</button>
		                @endif
                        <thead>
                      <tr>
                         <th><input type="checkbox"  id="My-Button"></th>
                        <th>Id</th>
                         <th>User</th>
                        <th>Company Name</th>
                         <th>InvoiceNo</th>
                        <th>Ricipient</th>
                        <th>Tracking ID</th>
                       
                        <th>Address</th>
                        <th>Phone</th>
                        
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
                        <td><input type="checkbox"  value="{{$value->id}}" name="parcel_id[]" form="myform"></td>
                          <td>{{$loop->iteration}}</td>
                          <td>{{@$value->user}}</td>
                          @php
                            $merchant = App\Models\Merchant::find($value->merchantId);
                           
                          @endphp
                           <td>{{$merchant->companyName}}</td>
                             <td>{{$value->invoiceNo}}</td>
                          <td>{{$value->recipientName}}</td>
                          <td>{{$value->trackingCode}}</td>
                         
                               
                               
                                
                          </td>
                          <td > {{$value->recipientAddress}} </td>
                          <td>  {{$value->recipientPhone}}</td>
                         
                         
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
                                 
                            
                                
                               
                               
                                 
                              
                                
                                 
                             
                                
                             
  
    
     @endif
                              </ul>
                              @endif
                          </td>
                            
                        </tr>
                        @endforeach
                      </tfoot>
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
