@extends('backEnd.layouts.master')
@section('title','Edit User')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <div class="box-content">
            <div class="row">
              <div class="col-sm-12">
                  <div class="manage-button">
                    <div class="body-title">
                      <h5>Edit Secret Agent</h5>
                    </div>
                    <div class="quick-button">
                      <a href="{{url('superadmin/sagent/manage')}}" class="btn btn-danger btn-actions btn-create">
                      Manage Secret Agent
                      </a>
                    </div>  
                  </div>
                </div>
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card card-danger">
                    <div class="card-header">
                      <h3 class="card-title">Edit info of <strong>{{$edit_data->name}}</strong></h3>
                    </div>
                    <!-- form start -->
                   <form action="{{url('superadmin/sagent/update')}}" method="POST" enctype="multipart/form-data" name="editForm">
                  @csrf
                  <input type="hidden" value="{{$edit_data->id}}" name="hidden_id">

                  <div class="main-body">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="name">Name</label>
                          <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{$edit_data->name}}">
                           @if ($errors->has('name'))
                            <span class="invalid-feedback">
                              <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                       
                      </div>
                      <!-- column end -->

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" name="username" id="username" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" value="{{$edit_data->username}}">
                          @if ($errors->has('username'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="email">Email</label>
                          <input type="email" name="email" id="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{$edit_data->email}}">
                          @if ($errors->has('email'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="phone">Phone</label>
                          <input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{$edit_data->phone}}">
                          @if ($errors->has('phone'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="designation">Designation</label>
                          <input type="text" name="designation" id="designation" class="form-control {{ $errors->has('designation') ? ' is-invalid' : '' }}" value="{{$edit_data->designation}}">
                          @if ($errors->has('designation'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('designation') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->
                     
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="image">Image</label>
                          <input type="file" name="image" id="image" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" value="{{old('image')}}">
                          <img src="{{asset($edit_data->image)}}" class="backend_image" alt="">
                          @if ($errors->has('image'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('image') }}</strong>
                              </span>
                            @endif
                        </div>
                      </div>
                      <!-- column end -->

                     
                      <div class="col-sm-12">
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                </div>
              </div>
              <!-- col end -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script type="text/javascript">
      document.forms['editForm'].elements['role_id'].value="{{$edit_data->role_id}}"
      document.forms['editForm'].elements['status'].value="{{$edit_data->status}}"
    </script>
@endsection