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
                          <h5>Manage Secret Agent</h5>
                        </div>
                        <div class="quick-button">
                          <a href="{{url('superadmin/sagent/add')}}" class="btn btn-danger btn-actions btn-create">
                          <i class="fa fa-plus"></i> Add New
                          </a>
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach($show_datas as $key=>$value)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$value->name}}</td>
                          <td>{{$value->email}}</td>
                          <td>{{$value->phone}}</td>
                          
                          <td>
                            <ul class="action_buttons">
                               
                                  <li>
                                      <a class="edit_icon" href="{{url('superadmin/sagent/edit/'.$value->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                  </li>
                                  <li>
                                    <form action="{{url('superadmin/sagent/delete')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" onclick="return confirm('Are you delete this this?')" class="trash_icon" title="Delete"><i class="fa fa-trash"></i></button>
                                    </form>
                                  </li>
                                  <li>
                                    <a href="{{url('superadmin/secrat-merchant/'.$value->id)}}" class="btn btn-warning">Merchant (<?php echo App\Models\SecratAgent::where('user_id',$value->id)->count(); ?>)</a>
                                  </li>
                              </ul>
                          </td>
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