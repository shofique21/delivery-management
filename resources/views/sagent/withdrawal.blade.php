@extends('sagent.layouts.app')
@section('title','Secret Agent Withdrawal Request')
@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif



        <div class="col-sm-12">
            <div class="manage-button">
                <div class="body-title p-2">
                    <h5> Withdrawal Request</h5>
                </div>

            </div>
        </div>

        <table id="" class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Merchant</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withd as $key=>$value)
                <tr>
                    <td>{{$value->id}}</td>
                    <td><?php $merhant= App\Merchant::where('id',@$value->merchant_id)->first(); ?>{{@$merhant->firstName}}
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
                        <span class="btn btn-sm btn-danger">Unpaid</span>
                        @else
                        <span class="btn btn-sm btn-info">Paid</span>
                        @endif
                    </td>
                </tr>

                @endforeach

        </table>

        <!-- /.card-body -->

        <!-- /.card -->



    </div>
</section>

<!-- Modal Section  -->
<!-- Modal -->




@endsection