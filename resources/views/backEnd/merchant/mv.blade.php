@extends('backEnd.layouts.master')
@section('title','Manage Merchant')
@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="box-content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                        <div class="col-sm-12">
                            <div class="manage-button">
                                <div class="body-title">
                                    <h5>Manage Merchant</h5>
                                    <p class=""><a class="btn btn-sm btn-success"
                                            href="{{url('editor/merchant/unpaid')}}">UnPaid Merchant </a></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="merchant" class="data-table table table-bordered table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Company Name</th>
                                        <th>Discount</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>



                                </tbody>
                                <tfoot>

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
    
<!-- merchant type -->
<div class="modal fade" id="mechantType" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Merchant type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           
                                                              
                                                               <input type="hidden" name="hidden_id" value="" id="hidden_id">
                                                                <!--<input type="hidden" name="hidden_id"  value="">-->
                                                                <select class="form-control" id="type">
                                                                    <option value="1">Prepaid</option>
                                                                    <option value="2">Postpaid</option>
                                                                </select>
    
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button  data-dismiss="modal" type="button" id="updateaType" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                       
    
          </div>
    </div>
  </div>
  
  <!-- merchant type -->
<div class="modal fade" id="mechantCod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cod Charge</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           
                                                              
                                                               <input type="hidden" name="hidden_id" value="" id="hidden_id">
                                                               <label>Cod charge (%)</label>
                                                                <input type="number" name="ecod" class="form-control"  id="ecod" value="" >
                                                                
    
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button  data-dismiss="modal" type="button" id="updateaCod" class="btn btn-primary">Save
                                                                changes</button>
                                                        </div>
                                                       
    
          </div>
    </div>
  </div>
</div>
</section>
<!-- Modal Section  -->

<!-- Modal -->

@endsection

@section('page-script')
<script type="text/javascript">
$(function() {

    var table = $('#merchant').DataTable({
       
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],

        "dom": 'Blfrtip',
        "buttons": [
            'excel', 'pdf', 'copy', 'print'
        ],
        processing: true,
        serverSide: true,
        // $.fn.dataTable.ext.errMode = 'throw';

        // data:data,
        ajax: "{{ url('editor/merchant/manage') }}",
        orderCellsTop: true,
        fixedHeader: true,

        // "columns": [{
        //         data: 'DT_RowIndex',
        //         orderable: false,
        //         searchable: true
        //     },
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'firstName',
                name: 'firstName'
            },
            {
                data: 'companyName',
                name: 'companyName'
            },
            {
                data: 'discount',
                name: 'discount'
            },
            {
                data: 'phoneNumber',
                name: 'phoneNumber'
            },
            {
                data: 'emailAddress',
                name: 'emailAddress'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        // stateSave: true,
        // dom: 'Bfrtip',
        // buttons: [
        // 'copy', 'csv', 'excel', 'pdf', 'print'
        // ]
    });




});
</script>
<script>
// merchant type start
$(document).on('click', '.merchantid', function() {
        // alert($(this).attr('mid'));
       
        $('#hidden_id').val($(this).attr('mid'));
    });
$(document).on('click', '#updateaType', function() {

        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //console.log($roomid);
        $info_url = url + '/editor/merchant/merchantType';
        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {
                hidden_id: $('#hidden_id').val(),
                type: $('#type').val(),
                

            },
            success: function(data) {
                if (data) {
                    $('#merchant').DataTable().ajax.reload();
                    if (data.success == 1) {
                        
                        toastr.success('Merchant Type Update successfully!');

                    } else {
                        
                        toastr.error('Merchant Type Update not successfully!');

                    }


                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
// merchant type end

// merchant cod chaRge start
$(document).on('click', '.merchantCod', function() {
        // alert($(this).attr('mid'));
       
        $('#hidden_id').val($(this).attr('mid'));
        $('#ecod').val($(this).attr('cod'));
    });
$(document).on('click', '#updateaCod', function() {

        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //console.log($roomid);
        $info_url = url + '/editor/merchant/merchantCod';
        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {
                hidden_id: $('#hidden_id').val(),
                ecod: $('#ecod').val(),
                

            },
            success: function(data) {
                if (data) {
                    $('#merchant').DataTable().ajax.reload();
                    if (data.success == 1) {
                        
                        toastr.success('Merchant Cod Charge Update successfully!');

                    } else {
                        
                        toastr.error('Merchant Cod Charge Update not successfully!');

                    }


                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
// merchant cod charge end
</script>
@endsection