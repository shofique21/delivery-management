@extends('backEnd.layouts.master')
@section('title','Merchant Invoice Report')
@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="box-content">
            <form action="{{url('editor/merchant/payment/dailyinvoice')}}" method="get">
                
               @csrf
                <h5>Merchant Invoice Report</h5>
        <div class="row pl-5">
            
            <div class="col-md-4">
                <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="{{@$to}}">
            </div>
            <div class="col-md-4">
                 <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="{{@$end}}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-success">Submit </button>
            </div>
           
           
        </div>
         </form>
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card custom-card">
                    <div class="col-sm-12">
                      <div class="manage-button">
                        <div class="body-title">
                          
                        </div>
                      </div>
                    </div>
                  <div class="card-body">
                    <table id="exampled" class="table table-bordered table-striped custom-table">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th>Date</th>
                        <th>Merchant</th>
                        <th>Total Invoice</th>
                        <th>Total Payment</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                          
                        @foreach($merchantInvoice as $key=>$value)
                         @php
                            $totalpayment = App\Models\Parcel::where('paymentInvoice',$value->id)->sum('cod')-(App\Models\Parcel::where('paymentInvoice',$value->id)->sum('deliveryCharge')+App\Models\Parcel::where('paymentInvoice',$value->id)->sum('codCharge'));
                            $totalinvoice = App\Models\Parcel::where('paymentInvoice',$value->id)->count();
                         @endphp
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td>{{$value->created_at}}</td>
                          <td><?php $m=App\Models\Merchant::where('id',$value->merchantId)->first(); ?>{{@$m->companyName}}</td>
                          <td>{{$totalinvoice}}</td>
                          <td>{{$totalpayment}}</td>
                          <td>
                            <ul class="action_buttons dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action Button
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                  <li>
                                      <a class="edit_icon" href="{{url('editor/merchant/payment/invoice-details/'.$value->id)}}" title="View"><i class="fa fa-eye"></i> View</a>
                                  </li>
                              </ul>
                          </td>
                        </tr>
                        @endforeach
                      </tfoot>
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