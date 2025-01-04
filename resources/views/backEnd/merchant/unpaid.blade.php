@extends('backEnd.layouts.master')
@section('title','Unpaid Merchant')
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
                                    <h5>Unpaid Merchant</h5>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="exampled" class="table table-bordered table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Company Name</th>
                                      
                                        <th>Phone</th>
                                        <th>Email</th>
                                    
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($merchants as $key=>$value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->firstName}} {{$value->lastName}}</td>
                                        <td>{{$value->companyName}}  <?php $upaid=App\Models\Parcel::where('merchantId',$value->merchantId)->where('merchantpayStatus',null) ->whereIn('parcels.status',[4,8])->count(); ?> @if($upaid)<span class="btn btn-sm btn-danger">unpaid({{$upaid}})</span>@endif</td>
                                        
                                        <td>{{$value->phoneNumber}}</td>
                                        <td>{{$value->emailAddress}}</td>
                                        
                                        <td>
                                            <ul class="action_buttons dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Action Button
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    
                                                    <li>
                                                        <a class="thumbs_up"
                                                            href="{{url('editor/merchant/edit/'.$value->merchantId)}}"
                                                            title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                                    </li>
                                                    <li>
                                                        <a class="edit_icon"
                                                            href="{{url('editor/merchant/view/'.$value->merchantId)}}"
                                                            title="View"><i class="fa fa-eye"></i> View</a>
                                                    </li>
                                                    <li>
                                                        <a class="edit_icon"
                                                            href="{{url('editor/merchant/payment/invoice/'.$value->merchantId)}}"
                                                            title="View"><i class="fa fa-list"></i> Invoice</a>
                                                    </li>
                                                    <li>
                                                        <a href="{{url('editor/merchant/dis/'.$value->merchantId)}}" class="btn btn-sm btn-dark">Discount</a>                                           

                                                    </li>
                                                </ul>
                                        </td>
                                        <div class="modal fade " id="examp{{$value->id}}" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            Discount</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                  

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                    </div>
                                                    </form>
                                                
                                                </div>

                                            </div>
                                        </div>
                                        
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

<!-- Modal -->

@endsection