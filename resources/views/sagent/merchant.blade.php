@extends('sagent.layouts.app')
@section('title','Secret Agent Merchant')
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
                    <h5> Merchant Transaction history</h5>
                </div>

            </div>
        </div>

        <table id="" class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Merchant</th>
                    <th>Commission (Rate)</th>
                    <th>Delivered Parcel</th>
                    <th>Commission</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agent as $key=>$value)
                <tr>
                    <td>{{$value->id}}</td>
                    <td><?php $merhant= App\Merchant::where('id',@$value->merchant_id)->first(); ?>{{@$merhant->firstName}}
                        <br>
                        {{@$merhant->companyName}} <br>
                        {{@$merhant->phoneNumber}} <br>
                        {{@$merhant->emailAddress}}
                    </td>
                    <td>{{$value->commision}}</td>
                    <td><?php $dparcel= App\Parcel::where('merchantId',$value->merchant_id)->where('status',4)->count(); ?>
                        {{$dparcel}}
                    </td>
                    <td><?php $commision= (App\Parcel::where('merchantId',$value->merchant_id)->where('status',4)->count()*$value->commision); ?>
                        {{$commision}}
                    </td>

                    <td>


                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#exampleModal{{$value->id}}">
                            Withdrawal Request
                        </button>
                        <div class="modal fade" id="exampleModal{{$value->id}}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                            Withdrawal Request
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{url('sagent/withdrawal-request')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="mid" value="{{$value->merchant_id}}">
                                            <label for="" class="label">Amount</label>
                                            <input type="text" class="form-control" name="amount" id=""
                                                value="{{$commision}}" readonly>
                                            <select name="pay_method" id="" class="form-control">
                                                <option value="">Select One</option>
                                                <option>Bkash</option>
                                                <option>Nagad</option>
                                                <option>Rocket</option>
                                            </select>
                                            <input type="text" class="form-control" name="number" id=""
                                                placeholder="Payment Number">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save
                                            changes</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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