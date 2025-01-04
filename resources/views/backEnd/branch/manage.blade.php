@extends('backEnd.layouts.master')
@section('title','Manage Branch')
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
                          <h5>Manage Branch</h5>
                        </div>
                          @if(Auth::user()->role_id <= 2  )  
                        <div class="quick-button">
                          <a href="{{url('editor/branch/add')}}" class="btn btn-primary btn-actions btn-create">
                          <i class="fa fa-plus"></i> Add New Branch
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
                        <th>Branch Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                          <th>Area</th>
                           <th>Images</th>
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
                          <td>{{$value->address}}</td>
                          <td>{{$value->phone}}</td> 
                          <td>{{$value->zonename}}</td>
                          <td><img src="{{asset($value->images)}}" class="backend_image" alt=""></td>
                          <td>{{$value->status==1? "Active":"Inactive"}}</td>
                            @if(Auth::user()->role_id <= 2  )  
                          <td>
                            <ul class="action_buttons">
                                <li>
                                  @if($value->status==1)
                                  <form action="{{url('editor/branch/inactive')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <button type="submit" class="thumbs_up" title="unpublished"><i class="fa fa-eye"></i></button>
                                  </form>
                                  @else
                                    <form action="{{url('editor/branch/active')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" class="thumbs_down" title="published"><i class="fa fa-eye-slash"></i></button>
                                    </form>
                                  @endif
                                </li>
                                  <li>
                                      <a class="edit_icon" href="{{url('editor/branch/edit/'.$value->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                  </li>
                                  <li>
                                    <form action="{{url('editor/branch/delete')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" onclick="return confirm('Are you delete this this?')" class="trash_icon" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                  </li>
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