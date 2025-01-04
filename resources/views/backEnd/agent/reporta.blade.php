@extends('backEnd.layouts.master')
@section('title','Hub report')
@section('content')
<style>
    @media (max-width: 1500px){
    .table td, .table th {
        max-width: 10rem;
    }
    }
</style>
<form action="{{url('admin/asing/report')}}" method="get">
@csrf
    <div class="row mt-2 mb-3">
        <div class="col">
            <select name="agent" id="" class="form-control">
            <option value="">Select Agent</option>
                @foreach($agent as $a)
                <option <?php if ($aid==$a->id) {
                  echo "selected";
                } ?> value="{{$a->id}}">{{$a->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate" value="{{$dates}}">
        </div>
        <!-- col end -->
        <div class="col">
            <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate" value="{{$datee}}">
        </div>
        <div class="col">
            <input type="submit" class="btn btn-primary form-control" value="submit">
        </div>
    </div>
</form>
<div class="card-body">
    <table id="example22" class="table table-bordered table-striped custom-table table-responsive">
        <thead>
            <tr>
                <th>Id</th>
                <th>Company Name</th>
                <th>Ricipient</th>
                <th>InvoiceNo</th>
                <th>Tracking ID</th>

                <th>Address</th>
                <th>Phone</th>
                <th>Rider</th>
                <th>Agent</th>
                <th>C. Update</th>
                <th>Status</th>
                <th>Total</th>
                <th>Charge</th>
                <th>Sub Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach($parcels as $key=>$value)
            <tr>
                <td>{{$loop->iteration}}</td>
                @php
                $merchant = App\Merchant::find($value->merchantId);
                $agentInfo = App\Agent::find($value->agentId);
                $deliverymanInfo = App\Deliveryman::find($value->deliverymanId);
                @endphp
                <td>{{$merchant->companyName}}</td>
                <td>{{$value->recipientName}}</td>
                <td>{{$value->invoiceNo}}</td>
                <td>{{$value->trackingCode}}</td>

                <td>{{$value->recipientAddress}}</td>
                <td>{{$value->recipientPhone}}</td>
                <td>@if($value->deliverymanId) {{$deliverymanInfo->name}} @endif</td>
                <!-- Modal -->

                <!-- Modal end -->
                <td>@if($value->agentId) {{$agentInfo->name}} @endif</td>
                <!-- Modal -->

                <!-- Modal end -->
                <td>{{date('F d, Y', strtotime($value->present_date))}}</td>
                <td><?php    $parceltype = App\Parceltype::where('id',$value->status)->first(); ?>{{@$parceltype->title}}
                </td>
                <td> {{$value->cod}}</td>
                <td> {{$value->deliveryCharge+$value->codCharge}}</td>
                <td> {{$value->cod-($value->deliveryCharge+$value->codCharge)}}</td>

            </tr>
            @endforeach
       
    </table>
    <table id="" class="table  table-striped">
        <thead>
            <tr>
                <th>Parcel</th>
                <th>Delivered</th>
                <th> Panding</th>
                <th>Cancelled</th>
                <th>Returned to Merchant</th>
                <th>Picked</th>
                <th>In Transit</th>
                <th>Hold</th>
                <th>Return Pending</th>
                <th>Return To Hub</th>

                <th>Cod Price</th>
                <th>Delivery Charge</th>
                <th>Cod Charge</th>
                <th>Collected Amount</th>


            </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    {{$parcelcount}}
                </td>
                <td>
                    {{$parcelr}}@if($parcelr)({{round(($parcelr*100)/$parcelcount,2)}}%)
                    @endif
                </td>
                <td>{{$parcelpa}}@if($parcelpa)({{round(($parcelpa*100 )/$parcelcount,2)}}%)
                    @endif
                </td>
                <td>{{$parcelc}}@if($parcelc)({{round(($parcelc*100)/$parcelcount,2)}}%)
                    @endif</td>
                <td>{{$parcelre}}@if($parcelre)({{round(($parcelre*100)/$parcelcount,2)}}%)@endif</td>
                <td>{{$parcelpictd}}@if($parcelpictd)({{round(($parcelpictd*100)/$parcelcount,2)}}%)@endif
                </td>
                <td>{{$parcelinterjit}}@if($parcelinterjit)({{round(($parcelinterjit*100)/$parcelcount,2)}}%)@endif
                </td>
                <td>{{$parcelhold}}@if($parcelhold)({{round(($parcelhold*100)/$parcelcount,2)}}%)
                    @endif</td>
                <td>{{$parcelrrtupa}}@if($parcelrrtupa)({{round(($parcelrrtupa*100)/$parcelcount,2)}}%)@endif
                </td>
                <td>{{$parcelrrhub}}@if($parcelrrhub)({{round(($parcelrrhub*100)/$parcelcount,2)}}%)
                    @endif</td>

                <td>{{$parcelpriceCOD}}</td>
                <td>{{$deliveryCharge}}</td>
                <td>{{$codCharge}}</td>
                <td>{{$Collectedamount}}</td>


            </tr>

        </tbody>
    </table>
</div>

@stop