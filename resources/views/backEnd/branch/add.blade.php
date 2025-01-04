@extends('backEnd.layouts.master')
@section('title','Create Branch')
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
                      <h5>Create Branch</h5>
                    </div>
                    <div class="quick-button">
                      <a href="{{url('editor/branch/manage')}}" class="btn btn-primary btn-actions btn-create">
                      Manage Branch
                      </a>
                    </div>  
                  </div>
                </div>
              <div class="col-md-12 col-sm-12">
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Add A New Branch</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
         <form action="{{url('editor/branch/save')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="main-body">
                    <div class="row">
                         <div class="col-sm-6">
                        <div class="form-group">
                          <label for="name">Branch Name</label>
                          <input type="text" name="name" id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}">
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
                          <input type="text" name="address" id="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}">
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
                          <input type="text" name="phone" id="phone" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}">
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
                          <label for="image">Image</label>
                          <input type="file" name="image" id="image" class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" value="{{ old('image') }}">
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
                          <label for="area">Area</label>
                          <select name="area" id="area" class="form-control select2 {{ $errors->has('area') ? ' is-invalid' : '' }}" value="{{ old('area') }}">
                            <option value="">Select...</option>
                            @foreach($areas as $value)
                            <option value="{{$value->id}}">{{$value->zonename}}</option>
                            @endforeach
                          </select>
                          @if ($errors->has('area'))
                              <span class="invalid-feedback">
                                <strong>{{ $errors->first('area') }}</strong>
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
                      <div class="col-sm-12 mrt-30">
                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">Submit</button>
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
@endsection