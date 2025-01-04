@extends('backEnd.layouts.master')
@section('title',' Secret Agent Withdrawal Request')
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
                          <h5> Secret Agent Withdrawal Request</h5>
                        </div>
                       
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped custom-table">
                    <thead>
                <tr>
                    <th>Id</th>
                    <th>User</th>
                    <th>Merchant</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Number</th>
                    <th>Action</th>
                </tr>
            </thead>
                      <tbody>
                      @foreach($withdra as $key=>$value)
                <tr>
                    <td>{{$value->id}}</td>
                    <td><?php $user= App\Models\User::where('id',$value->user_id)->first();?> {{@$user->name}} <br>
                    {{@$user->email}}<br>
                    {{@$user->phone}}</td>
                    <td><?php $merhant= App\Models\Merchant::where('id',@$value->merchant_id)->first(); ?>{{@$merhant->firstName}}
                        <br>
                        {{@$merhant->companyName}} <br>
                        {{@$merhant->phoneNumber}} <br>
                        {{@$merhant->emailAddress}}
                    </td>
                    <td>{{$value->amount}}</td>
                    <td>{{$value->pay_method}}
                    </td>
                    <td>
                        {{$value->number}}
                    </td>

                    <td>
                        @if($value->status==null)
                        <a href="{{url('superadmin/sagent/paid/'.$value->id)}}" class="btn btn-sm btn-danger">Unpaid</a>
                       
                        @else
                        
                        <span class="btn btn-sm btn-info">Paid</span>
                        @endif
                    </td>
                </tr>

                @endforeach

                     
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