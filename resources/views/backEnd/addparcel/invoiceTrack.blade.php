@extends('backEnd.layouts.master')
@section('title','Create Parcel')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    
                    
                    <li class="breadcrumb-item active">Parcel Status Change</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="row">
                 <div class="col-md-4" >
                   <select name="idelivermanId" id="idelivermanId" class="form-control select2" >
                        <option value="">Select Deliveryman</option>
                        @foreach($deliverymen as $men)
                        <option value="{{$men->id}}">{{$men->name}}</option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-md-4" >
                    <select name="status" id="istatus" class="form-control">
                        <option value="">Select Status</option>
                                                <option value="2">Picked</option>
                                                <option value="3">In Transit</option>
                                                <option value="5">Hold</option>
                                                <option value="6">Return Pending</option>
                                                <option value="8">Returned To Merchant</option>
                                    </select>
                   
                </div>

                 <div class="col-md-4" >
                    <input class="form-control" type="text" id="invoiceNo" name="invoiceNo" placeholder="Enter Invoice..."
                        search>
                </div>
            </div>
</section>
@endsection
@section('page-script')
 <script>
        $(document).ready(function() {
            $('#invoiceNo').on('keyup', function(e) {

                e.preventDefault();
                if (e.which == 13) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var ber = $("#invoiceNo").val();
                    var st = $("#istatus").val();
                    var dliveid = $("#idelivermanId").val();
                    console.log(ber);
                    
                    if (ber) {
                        $.ajax({
                            cache: false,
                            type: "POST",
                            url: "{{url('/editor/parcel/invoicetrack/')}}",
                            dataType: "json",
                            data: {
                                invoiceNo: ber,
                                istatus: st,
                                idelivermanId: dliveid
                            },
                            success: function(data) {
                                console.log(data);
                                if (data.success == 1) {
                                    $("#invoiceNo").val(null);
                                    toastr.success('Parcel status update successfully');

                                } else {
                                    $("#invoiceNo").val(null);
                                    toastr.error('Invoice Nuember is Not found !');

                                }


                            }
                        });
                    }
                }

            });
        });
        </script>
@endsection