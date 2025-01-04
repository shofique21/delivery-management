@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Payments')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
                  <div class="row">
                    <div class="col-sm-12">
                      <h4 style="margin-bottom: 10px;">Payments</h4>
                    </div>
                     <div class="col-sm-12">
                         <div class="payments-inner table-responsive-sm">
                           <table id="exampled" class="table  table-striped">
                             <thead>
                      <tr>
                        <th>Id</th>
                        <th>Date</th>
                        <th>Total Invoice</th>
                        <th>Total Cod</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                       <tbody>
                             @foreach($merchantInvoice as $key=>$value)
                              @php
                                $totalpayment = App\Models\Parcel::where('paymentInvoice',$value->id)->sum('cod');
                                $totalinvoice = App\Models\Parcel::where('paymentInvoice',$value->id)->count();
                             @endphp
                            <tr>
                             <tr>
                              <td>{{$loop->iteration}}</td>
                              <td>{{$value->created_at}}</td>
                              <td>{{$totalinvoice}}</td>
                              <td>{{round($totalpayment,2)}}</td>
                              <td> <a class="btn btn-primary" href="{{url('merchant/payment/invoice-details/'.$value->id)}}" title="View"><i class="fa fa-eye"></i> View</a></td>
                             </tr>
                             @endforeach
                              </tbody>
                           </table>
                         </div>
                      </div>
                  </div>
        </div>
    </div>
    <!-- row end -->
</div>


@endsection