@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title','Deliveryman Transaction')
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
                                    <h5>Deliveryman Transaction</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row " id="examp">
                                <div class="col-md-6" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Transaction</h5>

                                        </div>
                                        <div class="modal-body">
                                        <table id="examplek" class="table  table-striped">
                                            <thead>
                                              <tr>
                                                
                                                  <th>Date</th>
                                                  <th>Amount</th>
                                                 
                                              </tr>
                                              </thead>  
                                              <tbody>
                                              @foreach($t as $ta)
                                          <tr>
                                              
                                              <td>{{$ta->date}}</td>
                                              <td>{{round($ta->collectedamount,2)}}</td>
                                              
                                          </tr>
                                          
                                              @endforeach
                                              <tr>
                                              <th>Total </th>
                                              <th>{{round($Collected,2)}} </th>
                                          </tr>
                                              {{$t->links()}}
                                              </tbody>
                                          </table>

                                            <form action="{{url('deliveryman/transactions')}}" method="post">
                                                @csrf

                                                <label for=""><u>Send Amount for Hub</u>  </label>

                                                <input type="text" name="amount" id="" class="form-control">

                                        </div>

                                        <div class="modal-footer">

                                            <button type="submit" class="btn btn-primary">Send
                                                </button>
                                        </div>
                                        </form>

                                    </div>

                                </div>

                                <div class="col-md-6" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Transaction History</h5>
                                            
                                        </div>
                                        <div class="modal-body">

                                        <table id="example" class="table  table-striped">
                                              <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>User</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($transaction as $tran)
                                              
                                            <tr>
                                                <td>{{$tran->id}}</td>
                                                <td>{{$tran->user}}</td>
                                                <td>{{$tran->created_at}}</td>
                                                <td>{{round($tran->amount,2)}}</td>
                                                <td>@if($tran->status==null)
                                                  <span class="btn btn-sm btn-danger">Padding</span>
                                                  @else
                                                  <span class="btn btn-sm btn-success">Accepted </span>
                                                  @endif
                                                </td>
                                            </tr>
                                            
                                                @endforeach
                                             
                                                </tbody>
                                            </table>

                                        </div>


                                    </div>

                                </div>
                            </div>

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