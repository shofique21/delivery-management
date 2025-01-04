@extends('backEnd.layouts.master')
@section('title','Manage Agent')
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
                          <h5>Manage Agent</h5>
                        </div>
                          @if(Auth::user()->role_id <= 2  )  
                        <div class="quick-button">
                          <a href="{{url('admin/agent/add')}}" class="btn btn-primary btn-actions btn-create">
                          <i class="fa fa-plus"></i> Add New
                          </a>
                        </div>
                        @endif
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="exampled" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                          @if(Auth::user()->role_id <= 2  )  
                        <th>Action</th>
                        @endif
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_datas as $key=>$value)
                        <tr>
                          <td>{{$value->id}}</td>
                          <td>{{$value->name}}</td>
                          <td>{{$value->email}}</td>
                          <td>{{$value->phone}}</td>
                          <td>{{$value->status==1? "Active":"Inactive"}}</td>
                            @if(Auth::user()->role_id <= 2  )  
                          <td>
                            <ul class="action_buttons">
                                <li>
                                  @if($value->status==1)
                                  <form action="{{url('admin/agent/inactive')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <button type="submit" class="thumbs_up" title="unpublished"><i class="fa fa-thumbs-up"></i></button>
                                  </form>
                                  @else
                                    <form action="{{url('admin/agent/active')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" class="thumbs_down" title="published"><i class="fa fa-thumbs-down"></i></button>
                                    </form>
                                  @endif
                                </li>
                                  <li>
                                      <a class="edit_icon" href="{{url('admin/agent/edit/'.$value->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                      <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$value->id}}">
 <i class="fas fa-key"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Change Password </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="{{url('admin/agent/changepassword')}}" method="POST">
            @csrf
        <input type="hidden" name="hidden_id" value="{{$value->id}}">
        <input type="password" name="changepassword" class="form-control">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
       
         </form>
      </div>
    </div>
  </div>
</div>
                                  </li>
                                  <!--<li>-->
                                  <!--  <form action="{{url('admin/agent/delete')}}" method="POST">-->
                                  <!--    @csrf-->
                                  <!--    <input type="hidden" name="hidden_id" value="{{$value->id}}">-->
                                  <!--    <button type="submit" onclick="return confirm('Are you delete this this?')" class="trash_icon" title="Delete"><i class="fa fa-trash"></i></button>-->
                                  <!--  </form>-->
                                  <!--</li>-->
                              </ul>
                          </td>
                          @endif
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