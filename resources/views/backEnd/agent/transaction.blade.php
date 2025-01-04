@extends('backEnd.layouts.master')
@section('title','Transaction Request')
@section('content')
<form action="{{url('admin/hub/report')}}" method="get">
@csrf
    <div class="row p-2">
        
        <div class="col-sm-12">
        <section class="content">
    <div class="container-fluid">
        <div class="box-content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                       
                        <div class="card-body">
                            <div class="row " id="examp">
                               

                                <div class="col-md-12" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Transaction Request</h5>
                                            
                                        </div>
                                        <div class="modal-body">

                                        <table id="exampled" class="table  table-striped">
                                              <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Hub </th>
                                                    <th>Payment <br>Method</th>
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
                                                <td><?php $dman= App\Models\Agent::where('id',$tran->agent_id)->first(); ?>{{@$dman->name}}</td>
                                                <td>{{$tran->payment_method}} <br>
                                                {{$tran->account}}</td>
                                                <td>{{$tran->created_at}}</td>
                                                <td>{{$tran->amount}}</td>
                                                <td>@if($tran->status==null)
                                                  <span class="btn btn-sm btn-danger">Padding</span>
                                                  @else
                                                  <span class="btn btn-sm btn-success">Accepted </span>
                                                  @endif
                                                </td>
                                                <td>@if($tran->status==null)
                                                    <a href="{{url('admin/hub/transtion/'.$tran->id)}}" class="btn btn-sm btn-dark">Got it</a>
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
        </div>
    </div>
</form>


@stop