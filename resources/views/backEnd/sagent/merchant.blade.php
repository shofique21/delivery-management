@extends('backEnd.layouts.master')
@section('title','Manage Secret Agent')
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
                                    <h5> Secret Agent {{$user->name}}</h5>
                                </div>
                                <div class="quick-button">
                                    <a href="{{url('superadmin/sagent/add')}}"
                                        class="btn btn-danger btn-actions btn-create" data-toggle="modal"
                                        data-target="#exampleModal">
                                        <i class="fa fa-plus"></i> Add Merchant
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped custom-table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Merchant</th>
                                        <th>Commission</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($show_datas as $key=>$value)
                                    <tr>
                                        <td>{{$value->id}}</td>
                                        <td><?php $merhant= App\Models\Merchant::where('id',@$value->merchant_id)->first(); ?>{{@$merhant->firstName}}
                                            <br>
                                            {{@$merhant->companyName}} <br>
                                            {{@$merhant->phoneNumber}} <br>
                                            {{@$merhant->emailAddress}}
                                        </td>
                                        <td>{{$value->commision}}</td>


                                        <td>
                                            <ul class="action_buttons">

                                                <li><a href="{{url('superadmin/secrat-merchant/delete/'.$value->id)}}" class="btn btn-sm btn-danger">Delete</a></li>
                                                <li><a href="" class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#example{{$value->id}}">Commision</a>

                                                </li>


                                            </ul>
                                            
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="example{{$value->id}}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">
                                                            Commision Update</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{url('superadmin/merchant-commission-update')}}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id"
                                                                    value="{{$value->id}}">
                                                               
                                                                <input type="text" name="commision" id="" value="{{$value->commision}}"
                                                                    placeholder="commission" class="form-control mt-2">
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Merchant Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('superadmin/merchant-add')}}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <select name="merchant_id" id="" class="form-control">
                        @foreach($merchant as $mer)
                        <option value="{{$mer->id}}">{{$mer->companyName}}</option>
                        @endforeach
                    </select>
                    <input type="text" name="commision" id="" placeholder="commission" class="form-control mt-2">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection