@extends('backEnd.layouts.master')
@section('title','All Parcels')
@section('content')
<br/>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Custom Filter [Case Sensitive]</h3>
    </div>
    <div class="panel-body">
        <form method="POST" id="search-form" class="form-inline" role="form">

            <div class="form-group">
                <label for="name">trackId</label>
                <input type="text"  class="form-control input" placeholder="Track Id" name="trackId"
                                        id="trackId">
            </div>
            <div class="form-group">
                <label for="email">phoneNumber</label>
                <input type="number"  class="form-control input" placeholder="Phone Number"
                                        name="phoneNumber" id="phoneNumber">
            </div>

            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>
<table id="users" class="table table-condensed">
    <thead>
        <tr>
             <th>
                 <input type="checkbox" style="font-size: 14px;" class="bulkutton"></th>
                                        <th>Id</th>
                                        <th>User</th>
                                        <th>Invoice Number</th>
                                        <th>Company Name</th>
                                        <th>Ricipient</th>
                                        <th>Tracking ID</th>
                                        <th>Area</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>PickUpMan</th>
                                        
                                        <th>Rider</th>
                                        <th>Agent</th>
                                        <th>L. Update</th>
                                        <th>Status</th>
                                        <th>PCOD</th>
                                        <th>Total</th>
                                        
                                        <th>Charge</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                
        </tr>
    </thead>
</table>
<div id="asignPcikUpModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">PickUp Man Asign</h5>
                                </div>
                                <div class="modal-body">
                                 
                                    <input type="hidden" name="hidden_id" id="hidden_id" value="">
                                    <input type="hidden" name="merchant_phone" value="">
                                    <div class="form-group">
                                      <select name="pickupmanId" class="form-control" id="pickupmanId">
                                        <option value="">Select</option>
                                        @foreach($pickupmen as $key=>$pickupman)
                                        <option value="{{$pickupman->id}}">{{$pickupman->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <!-- form group end -->
                                    <div class="form-group">
                                      <textarea name="note" id="note" class="form-control" ></textarea>
                                    </div>
                                    <!-- form group end -->
                                    <div class="form-group">
                                      <button class="btn btn-success" id="pickupdate" data-dismiss="modal">Update</button>
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
                            <!-- Modal -->
                            <div id="asignModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Deliveryman Asign</h5>
                                        </div>
                                        <div class="modal-body">

                                            <input type="hidden" id="hidden_id" name="hidden_id" value="">
                                            <!-- <input type="hidden" id name="merchant_phone"
                                                                value=""> -->
                                            <div class="form-group">
                                                <select name="deliverymanId" class="form-control" id="deliverymanId">
                                                    <option value="">Select</option>
                                                    @foreach($deliverymen as $key=>$deliveryman)
                                                    <option value="{{$deliveryman->id}}">
                                                        {{@$deliveryman->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- form group end -->
                                            <div class="form-group">
                                                <textarea name="note" id="note" class="form-control"></textarea>
                                            </div>
                                            <!-- form group end -->
                                            <div class="form-group">
                                                <button class="btn btn-success" data-dismiss="modal" id="updateasignD"
                                                    type="button">Asign Deliveryman</button>
                                            </div>
                                            <!-- form group end -->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->


                            <!--hub Modal -->
                            <div id="agentModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Agent Asign</h5>
                                        </div>
                                        <div class="modal-body">
                                            <!-- <form action="{{url('editor/agent/asign')}}" method="POST">
                                                            @csrf -->
                                            <input type="hidden" name="hidden_id" id="ahidden_id" value="">
                                            <input type="hidden" name="merchant_phone" value="">
                                            <div class="form-group">
                                                <select name="agentId" class="form-control" id="agentid">
                                                    <option value="">Select</option>
                                                    @foreach($agents as $key=>$agent)
                                                    <option value="{{$agent->id}}">{{$agent->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <textarea name="note" id="note" class="form-control"></textarea>
                                            </div>

                                            <!-- form group end -->
                                            <div class="form-group">
                                                <button data-dismiss="modal" id="updateasignA"
                                                    class="btn btn-success">Agent Asign</button>
                                            </div>
                                            <!-- form group end -->
                                            <!-- </form> -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--hub Modal end -->
                            <!-- status start Modal -->
                            <div id="sUpdateModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Parcel Status Update
                                            </h5>
                                        </div>
                                        <div class="modal-body">

                                            <input type="hidden" name="hidden_id" value="" id="shidden_id">
                                            <input type="hidden" name="customer_phone" value="" id="customer_phone">
                                            <div class="form-group">
                                                <select name="status" class="form-control" id="sstatus" >
                                                  @foreach($parceltypes as $key=>$ptvalue)
                                                        <option value="{{$ptvalue->id}}">{{$ptvalue->title}}</option>
                                                        @endforeach              </select>
                                            </div>
                                            <!-- form group end -->
                                            <div class="form-group mrt-15">
                                                <select name="snote" id="snote" class="form-control">
                                                    <option value="">Select Note
                                                    </option>
                                                    <option value="Parcel Pending">
                                                        Parcel Pending</option>
                                                    <option value="Successfully Picked">
                                                        Successfully Picked</option>
                                                    <option value="Parcel in Transit">
                                                        Parcel in Transit</option>
                                                    <option value="Successfully Delivered">
                                                        Successfully Delivered</option>
                                                    <option value="Parcel in Hold">
                                                        Parcel in Hold</option>
                                                    <option value="Parcel Return Pending">
                                                        Parcel Return Pending</option>
                                                    <option value="Parcel returned to Hub">
                                                        Parcel returned to Hub</option>
                                                    <option value="Parcel returned to Merchant">
                                                        Parcel returned to Merchant
                                                    </option>
                                                    <option value="Parcel Cancelled">
                                                        Parcel Cancelled</option>
                                                         <option value="parcel pickup request Cancelled">
                                                        Parcel pickup request Cancelled</option>
                                                    <option value="Customer Refused  This Order! ">
                                                        Customer Refused This Order!
                                                    </option>
                                                    <option value="The customer couldnt receive this Parcel today.">
                                                        The customer couldnt receive
                                                        this Parcel today.</option>
                                                    <option value="The Customer will take this parcel tomorrow.">
                                                        The Customer will take this
                                                        parcel tomorrow.</option>
                                                    <option value="Returned from customer and hold in the hub!">
                                                        Returned from customer and hold
                                                        in the hub!</option>
                                                    <option value="The customer Didnt receive the call!">
                                                        The customer Didnt receive the
                                                        call!</option>
                                                    <option value="Sent to area HUB.">
                                                        Sent to area HUB.</option>
                                                    <option value="On the way to deliver!">
                                                        On the way to deliver!</option>
                                                    <option value="Wrong Product! return Pending!">
                                                        Wrong Product! return Pending!
                                                    </option>
                                                    <option value="This Parcel will be delivered tomorrow!">
                                                        This Parcel will be delivered
                                                        tomorrow!</option>


                                                </select>
                                            </div>
                                            <div class="form-group mrt-15">

                                                <p id="">Customize Note</p>

                                                <div id="myDIV">
                                                    <textarea name="note" id="inote" class="form-control" cols="30"
                                                        placeholder="Note"></textarea>
                                                </div>

                                            </div>
                                            <!-- form group end -->
                                            <div class="form-group">
                                                <div class="customerpaid" style="display: none;">
                                                    <input type="text" class="form-control"
                                                        value="" id="customerpay"
                                                        name="customerpay" placeholder="customer pay" /><br />
                                                </div>
                                            </div>
                                            <!-- form group end -->
                                            <div class="form-group">
                                                <button data-dismiss="modal" id="updateStatus"
                                                    class="btn btn-success">Update</button>
                                            </div>
                                            <!-- form group end -->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- status end Modal end -->
                            
                                  <!-- Modal -->
                            <div id="softDelete" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                       
                                        <div class="modal-body">

                                            <input type="hidden" id="dhidden_id" name="hidden_id" value="">
                                            <!-- <input type="hidden" id name="merchant_phone"
                                                                value=""> -->
                                           <h3>Are You Sure Delete this Parcel</h3>
                                            
                                            <!-- form group end -->
                                            <div class="form-group">
                                                <button class="btn btn-success" data-dismiss="modal" id="parcelDelete"
                                                    type="button">Delete</button>
                                            </div>
                                            <!-- form group end -->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
@endsection
@section('page-script')
<script>
var oTable = $('#users').DataTable({
        dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
            "<'row'<'col-xs-12't>>"+
            "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("/editor/custom-filter-data")  }}',
            data: function (d) {
                d.trackId = $('input[name=trackId]').val();
                d.phoneNumber = $('input[name=phoneNumber]').val();
            }
        },
        columns: [{
                    data: 'bulk',
                    name: 'bulk'
                }, {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'invoiceNo',
                    name: 'invoiceNo'
                },
                
                {
                    data: 'marchantName',
                    name: 'marchantName',
                   

                },
                {
                    data: 'recipientName',
                    name: 'recipientName',
                    

                },
                {
                    data: 'trackingCode',
                    name: 'trackingCode'
                },
                {
                    data: 'zonename',
                    name: 'zonename',
                  
                },
                {
                    data: 'recipientAddress',
                    name: 'recipientAddress'
                },
                {
                    data: 'recipientPhone',
                    name: 'recipientPhone'
                },
                {
                    data: 'pickupman',
                    name: 'pickupman',
                   
                },
                {
                    data: 'deliveyrman',
                    name: 'deliveyrman',
                    
                },
                {
                    data: 'agent',
                    name: 'agent',
                   
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'status',
                    name: 'status',
                    
                },
                 {
                    data: 'pcod',
                    name: 'pcod '
                },
                {
                    data: 'cod',
                    name: 'cod'
                },
               
                {
                    data: 'deliveryCharge',
                    name: 'deliveryCharge '
                },
                {
                    data: 'subtotal',
                    name: 'subtotal'
                },

                {
                    data: 'action',
                    name: 'action',
                   
                    orderable: false,
                    searchable: false
                    // data: null,
                    // name: null,
                    
                    // orderable: false
                },
                 
            ]
    });

    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    </script>
@endsection