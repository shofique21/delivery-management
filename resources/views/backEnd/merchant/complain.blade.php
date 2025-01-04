@extends('backEnd.layouts.master')
@section('title','Manage Complain')
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
                                    <h5>Merchant Complain</h5>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="dataexample" class="data-table table table-bordered table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Merchant</th>
                                        <th>Subject</th>
                                        <th>Type</th>
                                        <th>Issue</th>
                                        <th>Details</th>
                                        <th>Created</th>
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
  
</section>
<!-- Modal Section  -->

<!-- Modal -->

@endsection

@section('page-script')
<script type="text/javascript">

$(document).ready(function() {
    
$(function() {

  
    var table = $('#dataexample').DataTable({

        
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],

        "dom": 'Blfrtip',
        "buttons": [
            'excel', 'pdf', 'copy', 'print'
        ],
        searching: true,
        processing: true,
        serverSide: true,
     // "paging":   false,



        // data:data,
        ajax: "{{ url('editor/merchant/merchantComplain') }}",
        // orderCellsTop: true,
        // fixedHeader: true,
        
        columns: [{
                data: 'id',
                name: 'id'
            },{
                data: 'merchant',
                name: 'merchantId'
                
            },
            {
                data: 'subject',
                name: 'subject'
            },
            {
                data: 'issuetype',
                name: 'type_issue_id'
            },
            {
                data: 'issue',
                name: 'issue_id'
            },
            {
                data: 'details',
                name: 'details'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
                defaultContent: "",
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


$(document).on('click', '.status', function() {
    // alert(#hidden_id);

$('#hidden_id').val($(this).attr('cid'));
});
    
$(document).on('click', '#replyComplin', function() {
     $('select').change(function() {
        alert(this.value);
    });
        var url = "{{URL::to('/')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // var s =$('#statuss option:selected').val()
        // alert($( "#statuss :selected" ).val());
       
        // console.log($('#hidden_id').val());
        $info_url = url + '/editor/merchant/reply-complain';
        // alert($info_url);

        $.ajax({
            url: $info_url,
            method: "post",
            type: "POST",
            data: {    
                hidden_id: $('#hidden_id').val(),
                details: $('textarea#massage').val(),
                status: $('#statuss').val(),
            },
           
            success: function(data) {
               
                if (data) {
                    $('#dataexample').DataTable().ajax.reload();
                    if (data.success == 1) {
                       $("textarea#massage").val(null);
                        toastr.success('Complain Reply Successfully!');
                    } else {
                        $("textarea#massage").val(null);
                        // console.log(data);
                        toastr.error('Complain Reply Not Successfully!');

                    }

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
});
</script>
@endsection