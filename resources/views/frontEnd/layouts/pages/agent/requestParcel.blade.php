@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title','Dashboard')
@section('content')
<section class="profile-edit mrt-30">
   
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                        <div class="col-sm-12">
                            <div class="manage-button">
                                <div class="body-title">
                                    <h5> </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">

                            <div class="row">
                                <input type="hidden" class="input" value="" name="filter_id">
                                <div class="col">
                                    <input type="text"  class="form-control input" placeholder="Track Id" name="trackId"
                                        id="trackId">
                                </div>
                                <!-- col end -->
                                <div class="col">
                                    <input type="number"  class="form-control input" placeholder="Phone Number"
                                        name="phoneNumber" id="phoneNumber">
                                </div>
                                <!-- col end -->
                                <div class="col">
                                    <input type="number"  class="form-control input" placeholder="Merchant Id"
                                        name="merchantId" id="merchantId">
                                </div>
                                <div class="col">
                                <select name="status" id="statusSearch" class="form-control">
                                    <option value="" > Select Status
                                                    </option>
                                                     <option value="3">In Transit</option>
                                                    <option value="5">Hold</option>
                                                    <option value="4">Delivered</option>
                                                    <option value="6">Return Pending</option>
                                                    <option value="7">Returned To Hub</option>
                                                    <option value="10">Returned To Central Hub</option>
                                                </select>
                                                </div>
                                <!-- col end -->
                                <div class="col">
                                    <input type="date"  class="flatDate form-control input" placeholder="Date Form"
                                        name="startDate" id="startDate">
                                </div>
                                <!-- col end -->
                                <div class="col">
                                    <input type="date"  class="flatDate form-control input" placeholder="Date To"
                                        name="endDate" id="endDate">
                                </div>
                                <!-- col end -->
                                <div class="col">
                                    <!-- <button type="button" id="filters"  class="btn btn-success">Submit </button> -->
                                    <button type="button" name="filter" id="filters"
                                        class="btn btn-primary">Filter</button>
                                    <button type="button" name="refresh" id="refresh"
                                        class="btn btn-info">Refresh</button>

                                </div>
                                <!--<button type="button" id="export" data-export="export">Export</button>-->


                                <!-- col end -->
                            </div>

                        </div>
                     

                        <div class="card-body" id="datatable">

                            <table id="dataexample"
                                class=" table  table-striped table-responsive custom-table "
                                width="100%">
                                <thead>
                                    <tr>
                                      
                                        <th>Id</th>
                                        <th>User</th>
                                        <th>Invoice Number</th>
                                        <th>Company Name</th>
                                        <th>Ricipient</th>
                                        <th>Tracking ID</th>
                                        <th>Area</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        
                                        
                                        <th>Rider</th>
                                        <th>Agent</th>
                                        <th>L. Update</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        
                                        <th>Charge</th>
                                        <th>Sub Total</th>
                                        <th>Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>



                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                           
                            <!-- Modal -->
                           
                            <!-- Modal end -->


                            <!--hub Modal -->
                           
                            <!--hub Modal end -->
                            <!-- status start Modal -->
                           <div id="pAccOrRej" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Parcel Accepted Or Rejected</h5>
                                </div>
                                <div class="modal-body">
                                    
                                        <input type="hidden" name="hidden_id" id="hidden_id" value="">
                                        <div class="form-group">
                                            <select name="hubaprove"  class="form-control" id="hubaprove">
                                              <option value="1">Accept</option>
                                              <option value="0">Reject</option>
                                                    
                                            </select>
                                        </div>
                                        <!-- form group end -->
                                        <!-- form group end -->
                                        
                                    
                                     
                                        <!-- form group end -->
                                        <div class="form-group">
                                            <button id="updateaccept" data-dismiss="modal" class="btn btn-success">Update</button>
                                        </div>
                                        <!-- form group end -->
                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
                            <!-- Modal end -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
       
</section>
<!-- Modal -->
@endsection
@section('js')

<script>

$(document).ready(function() {
    // load_data();


    $(function load_data(filter_id='',trackId = '', phoneNumber = '', merchantId = '',status='', startDate = '', endDate = '') {
        // load_data();

        var table = $('#dataexample').DataTable({
        // "paging":   false,
            
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],

        "dom": 'Blfrtip',
        "buttons": [
            'excel', 'pdf', 'copy', 'print'
        ],
        
        processing: true,
        "language": {
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
        serverSide: true,
        searching: false,
               
            // data:data,
            // $('.data-table').DataTable().destroy();
            // load_data();
            ajax: {
                url: '{{ url("agent/parcels-request") }}',
                data: {
                    filter_id: filter_id,
                    trackId: trackId,
                    phoneNumber: phoneNumber,
                    merchantId: merchantId,
                    status: status,
                    startDate: startDate,
                    endDate: endDate
                }
            },
            // paging: false,
        orderCellsTop: true,
        fixedHeader: true,
            // ajax: "{{ url('editor/parcel/all-parcel') }}",
            // data:{from_date:from_date, to_date:to_date}

            columns: [ {
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
                    defaultContent: ""

                },
                {
                    data: 'recipientName',
                    name: 'recipientName',
                    defaultContent: ""

                },
                {
                    data: 'trackingCode',
                    name: 'trackingCode'
                },
                {
                    data: 'zonename',
                    name: 'zonename',
                    defaultContent: ""
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
                    data: 'deliveyrman',
                    name: 'deliveyrman',
                    defaultContent: ""
                },
                {
                    data: 'agent',
                    name: 'agent',
                    defaultContent: ""
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'status',
                    name: 'status',
                    defaultContent: ""
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
                    defaultContent: "",
                    orderable: false,
                    searchable: false
                    // data: null,
                    // name: null,
                    
                    // orderable: false
                },
                 
            ]
            // stateSave: true,
            // dom: 'Bfrtip',
            // buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            // ]

        });

        // $('.input').click(function() {
           $('.input').on('keyup', function(e) {
                e.preventDefault();
                if (e.which == 13) {
            var filter_id = $('#filter_id').val();
            var trackId = $('#trackId').val();
            var phoneNumber = $('#phoneNumber').val();
            var merchantId = $('#merchantId').val();
            var status = $('#statusSearch').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            //  $('#filter_id').val(null);
            // $('#trackId').val(null);
            // $('#phoneNumber').val(null);
            // $('#merchantId').val(null);
            // $('#startDate').val(null);
            // $('#endDate').val(null);
            if (filter_id != null ||trackId != null || phoneNumber != null || merchantId != '' || pstatus != null || startDate !=
                null && endDate != null) {
               
                $('#dataexample').DataTable().destroy();
                load_data(filter_id, trackId, phoneNumber, merchantId, status, startDate, endDate);
            
            } else {
                alert(
                    'Both Date is required');
            }
                }
        });
        $('#filters').click(function() {
            var filter_id = $('#filter_id').val();
            var trackId = $('#trackId').val();
            var phoneNumber = $('#phoneNumber').val();
            var merchantId = $('#merchantId').val();
            var status = $('#statusSearch').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            if (filter_id != null ||trackId != null || phoneNumber != null || merchantId != '' || status != null || startDate !=
                null && endDate != null) {
                $('#dataexample').DataTable().destroy();
                load_data(filter_id,trackId, phoneNumber, merchantId, status, startDate, endDate);
            } else {
                alert(
                    'Both Date is required');
            }
        });
        $('#refresh').click(function() {
             $('#filter_id').val(null);
            $('#trackId').val(null);
            $('#phoneNumber').val(null);
            $('#merchantId').val(null);
            $('#statusSearch').val(null);
            $('#startDate').val(null);
            $('#endDate').val(null);
            $('#dataexample').DataTable().destroy();
            load_data();
        });

    });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
// asign pickupman start
$(document).on('click', '.picman', function() {
        //alert($(this).attr('rid'));
        $('#hidden_id').removeAttr('value');
        $('#deliverymanId').removeAttr('value');
        $('#note').html(null);
        $('#hidden_id').val($(this).attr('pid'));
    });

    $(document).on('click', '#pickupdate', function() {

        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //console.log($roomid);
        $info_url = url + '/editor/pickupman/asign';
        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {
                hidden_id: $('#hidden_id').val(),
                pickupmanId: $('#pickupmanId').val(),
                note: $('#note').val(),

            },
            success: function(data) {
                if (data) {
                    $('#dataexample').DataTable().ajax.reload();
                    if (data.success == 1) {
                        $("#pickupmanId").val(null);
                        $("#note").val(null);
                        toastr.success('A pickupman asign successfully!');

                    } else {
                        $("#deliverymanId").val(null);
                        $("#note").val(null);
                        toastr.error('A pickupman asign not successfully!');

                    }


                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
// asign pickupman end

    $(document).on('click', '.asingd', function() {
        //alert($(this).attr('rid'));
        $('#hidden_id').removeAttr('value');
        $('#deliverymanId').removeAttr('value');
        $('#note').html(null);
        $('#hidden_id').val($(this).attr('rid'));
    });

    $(document).on('click', '#updateasignD', function() {

        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //console.log($roomid);
        $info_url = url + '/agent/deliveryman/asign1';
        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {
                hidden_id: $('#hidden_id').val(),
                deliverymanId: $('#deliverymanId').val(),
                note: $('#note').val(),

            },
            success: function(data) {
                if (data) {
                    $('#dataexample').DataTable().ajax.reload();
                    if (data.success == 1) {
                        $("#deliverymanId").val(null);
                        $("#note").val(null);
                        toastr.success('A deliveryman asign successfully!');

                    } else {
                        $("#deliverymanId").val(null);
                        $("#note").val(null);
                        toastr.error('A deliveryman asign not successfully!');

                    }


                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    // asign deliveryman
    $(document).on('click', '.asinga', function() {
        // alert($(this).attr('aid'));
        // $('#hidden_id').removeAttr('value');
        // $('#deliverymanId').removeAttr('value');
        // $('#note').html(null);
        $('#ahidden_id').val($(this).attr('aid'));
    });

    $(document).on('click', '#updateasignA', function() {
alert('ddd');
        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //console.log($roomid);
        $info_url = url + '/agent/asigns';
        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {
                hidden_id: $('#ahidden_id').val(),
                agentId: $('#agentid').val(),
                note: $('#anote').val(),

            },
            success: function(data) {
                if (data) {
                    $('#dataexample').DataTable().ajax.reload();
                    if (data.success == 1) {
                        $("#agentid").val(null);
                        $("#anote").val(null);
                        toastr.success('A agent asign successfully!');

                    } else {
                        $("#agentid").val(null);
                        $("#anote").val(null);
                        toastr.error('A agent asign not successfully!');

                    }


                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    // parcel accept
    $(document).on('click', '.accepeted', function() {
        $('#hidden_id').val($(this).attr('sid'));
       
    });

    $(document).on('click', '#updateaccept', function() {
        alert($('#sstatus').val());
    //   alert($(#sstatus).children("option:selected").val()) ;
        
        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // alert()
        // console.log($('#note').val());
        $info_url = url + '/agent/parcel/acceptReturn1';
        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {
                hidden_id: $('#hidden_id').val(),
                hubaprove: $('#hubaprove').val(),
            },
            //  console.log(data);
            success: function(data) {
                
                if (data) {
                    $('#dataexample').DataTable().ajax.reload();
                    if (data.success == 1) {
                        $("#hubaprove").val(null);
                        toastr.success('Parcel successfully done!');

                    } else {
                        $("#hubaprove").val(null);
                       
                        toastr.error('Something is wrong !');

                    }
                    //  console.log(data);


                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
    
    
     // parcel Delete
   


   


});
</script>


@endsection