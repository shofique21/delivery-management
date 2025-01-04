@extends('backEnd.layouts.master')
@section('title','Edit Branch Info')
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
                      <h5>Edit Branch Info</h5>
                    </div>
                    <div class="quick-button">
                      <a href="{{url('editor/branch/manage')}}" class="btn btn-primary btn-actions btn-create">
                      Manage Branch Info
                      </a>
                    </div>  
                  </div>
                </div>
              <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Edit Branch Info</h3>
                    </div>
                    <!-- form start -->
                   <form action="{{url('editor/branch/update')}}" method="POST" enctype="multipart/form-data" name="editForm">
                  @csrf
                  <input type="hidden" value="{{$edit_data->id}}" name="hidden_id">

                  <div class="main-body">
                    <div class="row">
                           <div class="col-sm-6">
                        <div class="form-group">
                          <label for="name">Branch Name</label>
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
                          <label for="address">Address</label>
                          <input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{$edit_data->address}}">
                          @if ($errors->has('address'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('address') }}</strong>
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
                     
                      <!-- column end -->
                      <div class="col-sm-6">
                        <div class="form-group">
                            <label>Area</label>
                              <select name="area" class="form-control select2 {{ $errors->has('area') ? ' is-invalid' : '' }}">
                                <option value="">Select...</option>
                                @foreach($areas as $key=>$value)
                                <option value="{{$value->id}}" <?php if($value->id==$edit_data->area){ ?>selected="selected"<?php } ?>>{{$value->zonename}}</option>
                                @endforeach
                              </select>

                              @if ($errors->has('area'))
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('area') }}</strong>
                              </span>
                              @endif
                        </div>
                      </div>
                      
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
                      <div class="col-sm-6">
                        <div class="form-group">
                          <div class="custom-label">
                            <label>Publication Status</label>
                          </div>
                          <div class="box-body pub-stat display-inline">
                              <input class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" id="active" name="status" value="1">
                              <label for="active">Active</label>
                              @if ($errors->has('status'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('status') }}</strong>
                              </span>
                              @endif
                          </div>
                          <div class="box-body pub-stat display-inline">
                              <input class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" type="radio" name="status" value="0" id="inactive">
                              <label for="inactive">Inactive</label>
                              @if ($errors->has('status'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('status') }}</strong>
                              </span>
                              @endif
                          </div>
                        </div>
                      </div>
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
      document.forms['editForm'].elements['area'].value="{{$edit_data->id}}"
      document.forms['editForm'].elements['status'].value="{{$edit_data->status}}"
    </script>
@endsection