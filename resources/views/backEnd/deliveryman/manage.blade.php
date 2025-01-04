@extends('backEnd.layouts.master')
@section('title','Manage Deliveryman')
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
                          <h5>Manage Deliveryman</h5>
                        </div>
                        <div class="quick-button">
                          <a href="{{url('admin/deliveryman/add')}}" class="btn btn-primary btn-actions btn-create">
                          <i class="fa fa-plus"></i> Add New
                          </a>
                                                    <!-- Button trigger modal -->
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal">  <i class="fa fa-plus"></i> Add Commission
</button>
	
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="exampled" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Hub</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Commission</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_datas as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$value->name}}</td>
                          <td><?php $agent = App\Models\Agent::where('id',$value->agentId)->first();?>
                          {{@$agent->name}}</td>
                          <td>{{$value->email}}</td>
                          <td>{{$value->phone}}</td>
                          <td>{{$value->commission}} Taka</td>
                           <td>{{$value->jobstatus==1? "Delivary Man":"PickUp Man"}}</td>
                          <td>{{$value->status==1? "Active":"Inactive"}}</td>
                          <td>
                            <ul class="action_buttons">
                                <li>
                                  @if($value->status==1)
                                  <form action="{{url('admin/deliveryman/inactive')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <button type="submit" class="thumbs_up" title="unpublished"><i class="fa fa-thumbs-up"></i></button>
                                  </form>
                                  @else
                                    <form action="{{url('admin/deliveryman/active')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" class="thumbs_down" title="published"><i class="fa fa-thumbs-down"></i></button>
                                    </form>
                                  @endif
                                </li>
                                
                                <li>
                                    <!-- Button trigger modal -->
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal{{$value->id}}">
<i class="far fa-money-bill-alt"></i>
</button>
	<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
                  <form action="{{url('admin/deliveryman/defaultcommission')}}" method="POST" autocomplete="off">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Default Commission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                                      @csrf
                                      <input type="text" name="commission" class="form-control"  placeholder="Default Commission " value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
                                        </form>

  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
                  <form action="{{url('admin/deliveryman/commission')}}" method="POST" autocomplete="off">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Commission</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <input type="text" name="commission" class="form-control"  placeholder="Commission " value="{{$value->commission}}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
                                        </form>

  </div>
</div>
                                </li>
                                  <li>
                                      <a class="edit_icon" href="{{url('admin/deliveryman/edit/'.$value->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                  </li>
                                  
                                   <li>
                                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#delman{{$value->id}}">
                                        Change Password
                                      </button>
                                  </li>
                                  <li>
                                    <form action="{{url('admin/deliveryman/delete')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" onclick="return confirm('Are you delete this this?')" class="trash_icon" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                  </li>
                              </ul>
                          </td>
                        </tr>
<!--dalivery man password change-->
<div class="modal fade" id="delman{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{url('admin/deliveryman/changepassword')}}" method="POST">
            @csrf
        <input type="hidden" name="tid" value="{{$value->id}}">
        <input type="password" name="password" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
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




@endsection