@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title','Agent Transaction')
@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="box-content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                       
                        <div class="card-body">
                            <div class="row " id="examp">
                                <div class="col-md-4" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header bg-success">
                                            <h5 class="modal-title " id="exampleModalLabel">
                                            Agent transaction</h5>

                                        </div>
                                        <div class="modal-body">
                                            

                                            <form action="{{url('agent/transactions')}}" method="post">
                                                @csrf
                                                <label for="">Payment Method</label>
                                                <select name="payment_method" id="" class="form-control">
                                                    <option value="">Select One</option>
                                                    <option value="bkash">Bkash</option>
                                                    <option value="DBL(Rocket)">DBL (Rocket)</option>
                                                    <option value="Bank">Bank</option>
                                                </select>

                                                <input type="text" name="oacount" id="bk" class="form-control" placeholder="Trans Number ">
                                                <input type="text" name="acount" id="bn" class="form-control" placeholder="Bank Account " >
                                                <label for=""> Amount </label>

                                                <input type="text" name="amount" id="" class="form-control">

                                        </div>

                                        <div class="modal-footer">

                                            <button type="submit" class="btn btn-primary">Send
                                            </button>
                                        </div>
                                        </form>

                                    </div>

                                </div>

                                <div class="col-md-8" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-info">
                                            <h5 class="modal-title " id="exampleModalLabel">
                                                Transaction History</h5>

                                        </div>
                                        <div class="modal-body">

                                            <table id="exampled" class="table  table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Payment <br>Method</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transaction as $tran)

                                                    <tr>
                                                        <td>{{$tran->id}}</td>
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
@section('js')
<script>
$('#bk').hide();
$('#bn').hide();
$('select').on('change', function() {
  if( this.value=='Bank' ){
    $('#bn').show();
    $('#bk').hide();
  }else{
    $('#bn').hide();
    $('#bk').show();
  }
});

</script>
@endsection