@extends('backEnd.layouts.master')
@section('title','Check Pickup Request')

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
@media (max-width: 1500px){
.table th, .table td, table.dataTable thead th, table.dataTable tfoot th {
     max-width: 15rem !important; 
     padding: .5rem 1rem !important;
}
}

</style>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="box-content p-2">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card custom-card">
                    <h2>Today Pickup</h2>
                    <div class="card-body"  id="datatable">
                         <table id="example" class="table table-bordered table-striped custom-table table-responsive" width="100%">
                            <thead>
                          <tr>
                             <th>SL No.</th>
                             <th>Merchant</th>
                            <th>Creatiion Time</th>
                            <th>Pickup Address</th>
                            <th>Contact Number</th>
                            <th>Estimated Parcel Quantity</th>
                            <th>Assign HUB</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                        @foreach($today as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          @php
                            $merchant = App\Models\Merchant::find($value->merchantId);
                            $agentInfo = App\Models\Agent::find($value->agent);
                            $deliverymanInfo = App\Models\Deliveryman::find($value->deliveryman);
                          @endphp
                          <td>{{$merchant->companyName}} </td>
                           <td>{{date("g:i a", strtotime($value->time))}}, {{date('d M Y', strtotime($value->date))}}</td>
                        
                          <td>{{$value->pickupAddress}}</td>
                           <td>{{$value->phone}}</td>
                           <td>{{$value->estimedparcel}}</td>
                          <td>@if($value->agent!=0) {{$agentInfo->name}} @else <button class="btn btn-primary" data-toggle="modal" data-target="#asignModal{{$value->id}}">Asign</button> @endif</td>
                          
                          <td>@if($value->status==0) New @elseif($value->status==1) Pending @elseif($value->status==2) Accepted @elseif($value->status==3)Cancelled @endif</td>
                          <!-- Modal -->
                         
                          <!-- Modal end -->
                          <!-- Modal -->
                          <div id="asignModal{{$value->id}}" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Agent Asign</h5>
                                </div>
                                <div class="modal-body">
                                  <form action="{{url('editor/pickup/agent/asign')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <input type="hidden" name="merchant_phone"  value="{{$merchant->phoneNumber}}">
                                    <div class="form-group">
                                      <select name="agent" class="form-control" id="">
                                        @foreach($agents as $key=>$agent)
                                          <option value="{{$agent->id}}" name="agent">{{$agent->name}}</option>
                                        @endforeach
                                      </select>
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
                          <td>
                            <ul class="action_buttons">
                                  <li>
                                      <button class="thumbs_up" title="Action" data-toggle="modal" data-target="#sUpdateModal{{$value->id}}"><i class="fa fa-sync-alt"></i></button>
                                      <!-- Modal -->
                                      <div id="sUpdateModal{{$value->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                          <!-- Modal content-->
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Pickup Status Update</h5>
                                            </div>
                                            <div class="modal-body">
                                              @if($value->agent!=0)
                                              <form action="{{url('editor/pickup/status-update')}}" method="POST">
                                                @csrf
                                                <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                                <input type="hidden"  value="{{$value->status}}">
                                                <div class="form-group">
                                                    <select name="status"  class="form-control" id="" dissable="dissable">
                                                        <option value="1"@if($value->status==1) selected="selected" @endif>Pending</option>
                                                        <option value="2"@if($value->status==2) selected="selected" @endif>Accepted</option>
                                                        <option value="3"@if($value->status==3) selected="selected" @endif>Cancelled</option>
                                                  </select>
                                                </div>                                    
                                                <!-- form group end -->
                                                <div class="form-group">
                                                  <button class="btn btn-success">Update</button>
                                                </div>
                                                <!-- form group end -->
                                              </form>
                                              @else
                                              <h4>Please asign a agent first</h4>
                                              @endif
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <!-- Modal end -->
                                  </li>
                                  <li>
                                      <button class="edit_icon" href="#"  data-toggle="modal" data-target="#merchantParcel{{$value->id}}" title="View"><i class="fa fa-eye"></i></button>
                                      <div id="merchantParcel{{$value->id}}" class="modal fade" role="dialog">
                                  <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Pickup Details</h5>
                                      </div>
                                      <div class="modal-body">
                                        <table class="table table-bordered">
                                          <tr>
                                            <td>Merchant Name</td>
                                            <td>{{$merchant->firstName}} {{$merchant->lastName}}</td>
                                          </tr>
                                          <tr>
                                            <td>Merchant Phone</td>
                                            <td>{{$merchant->phoneNumber}}</td>
                                          </tr>
                                          <tr>
                                            <td>Merchant Email</td>
                                            <td>{{$merchant->emailAddress}}</td>
                                          </tr>
                                          <tr>
                                            <td>Company</td>
                                            <td>{{$merchant->companyName}}</td>
                                          </tr>
                                          <tr>
                                            <td>Pickup Address</td>
                                            <td>{{$value->pickupAddress}}</td>
                                          </tr>
                                          <tr>
                                            <td>Pickup type</td>
                                            <td>@if($value->pickuptype==1) Next Day Delivery @elseif($value->pickuptype==2) Same Day Delivery @endif</td>
                                          </tr>
                                          <tr>
                                            <td>Note</td>
                                            <td>{{$value->note}}</td>
                                          </tr>
                                          <tr>
                                            <td>Estimed Parcel</td>
                                            <td>{{$value->estimedparcel}}</td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal end -->
                                </li>
                              </ul>
                          </td>
                        </tr>
                        @endforeach
                          </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card custom-card">
                    <h2>Tomorrow Pickup</h2>
                    <div class="card-body"  id="datatable">
                         <table id="example2" class="table table-bordered table-striped custom-table table-responsive" width="100%">
                            <thead>
                          <tr>
                             <th>SL No.</th>
                             <th>Merchant</th>
                            <th>Creatiion Time</th>
                            <th>Pickup Address</th>
                            <th>Contact Number</th>
                            <th>Estimated Parcel Quantity</th>
                          
                           
                            <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                                @foreach($tomorrow as $key=>$tvalue)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          @php
                            $merchant = App\Models\Merchant::find($tvalue->merchantId);
                            $agentInfo = App\Models\Agent::find($tvalue->agent);
                            $deliverymanInfo = App\Models\Deliveryman::find($tvalue->deliveryman);
                          @endphp
                          <td>{{$merchant->firstName}} {{$merchant->lastName}}</td>
                           <td>{{date("g:i a", strtotime($tvalue->time))}}, {{date('d M Y', strtotime($tvalue->date))}}</td>
                        
                          <td>{{$tvalue->pickupAddress}}</td>
                           <td>{{$tvalue->phone}}</td>
                           <td>{{$tvalue->estimedparcel}}</td>
                          
                          
                          
                          <!-- Modal -->
                         
                          <!-- Modal end -->
                          <!-- Modal -->
                        
                        
                          <!-- Modal end -->
                          <td>
                            <ul class="action_buttons">
                                
                                  <li>
                                    <a href="{{url('editor/parcel/move-today/'.$tvalue->id)}}" class="btn btn-sm btn-info">Move Today</a>
                                     
                                <!-- Modal end -->
                                </li>
                              </ul>
                          </td>
                        </tr>
                        @endforeach
                          </tfoot>
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
  </section>
    <script>
       $(document).ready(function() {
          $('#example2').DataTable( {
              dom: 'Bfrtip',
              stateSave: true,
            "pageLength": 50,
          
       
              buttons: [
                  {
                      extend: 'copy',
                      text: 'Copy',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'csv',
                      text: 'Csv',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  
                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'print',
                      text: 'Print all',
                      exportOptions: {
                          modifier: {
                              selected: null
                          }
                      }
                  },
                  {
                      extend: 'colvis',
                  },
                  
              ],
              select: true
          } );
          
           table.buttons().container()
              .appendTo( '#example_wrapper .col-md-6:eq(0)' );
      });
</script>
@stop
