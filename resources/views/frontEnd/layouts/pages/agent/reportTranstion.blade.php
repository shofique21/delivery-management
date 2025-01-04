@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title',' Transaction Request')
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
                                    <h5> Transaction  Request</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row " id="examp">
                               

                                <div class="col-md-12" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Transaction Request</h5>
                                            
                                        </div>
                                        <div class="modal-body">

                                        <table id="example" class="table  table-striped">
                                              <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Delivery Man</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($transaction as $tran)
                                              
                                            <tr>
                                                <td>{{$tran->id}}</td>
                                                <td><?php $dman= App\Deliveryman::where('id',$tran->deliveryman_id)->first(); ?>{{@$dman->name}}</td>
                                                <td>{{$tran->created_at}}</td>
                                                <td>{{$tran->amount}}</td>
                                                <td>@if($tran->status==null)
                                                  <span class="btn btn-sm btn-danger">Padding</span>
                                                  @else
                                                  <span class="btn btn-sm btn-success">Accepted </span>
                                                  @endif
                                                </td>
                                                <td>@if($tran->status==null)
                                                    <a href="{{url('agent/transaction/transtionaccept/'.$tran->id)}}" class="btn btn-sm btn-dark">Got it</a>
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