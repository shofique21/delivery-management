@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title','Agent Report')
@section('content')
<form action="{{url('agent/report')}}" method="get">
@csrf
    <div class="row p-2 no-print">
        
        <div class="col-sm-2">
            <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
        </div>
        <!-- col end -->
        <div class="col-sm-2">
            <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
        </div>
        <div class="col-sm-2">
            <input type="submit" class=" form-control" value="submit">
            
        </div>
        <!-- <div class="col-sm-2">-->
        <!--    <input type="button" class="btn btn-sm btn-dark" onclick="window.print()" value="print">-->
        <!--</div>-->
    </div>
</form>

<div class="card-body">
    <table id="exampled" class="table table-bordered table-striped custom-table table-responsive">
        <thead>
            <tr>
                <th>Id</th>
                <th>Company Name</th>
                <th>Ricipient</th>
                <th>Tracking ID</th>
                <th>Invoice No </th>
                <th>Address</th>
                <th>Phone</th>
                <th>Rider</th>
                <th>Agent</th>
                 <th>Created</th>
                 <th>L.Update</th>
                <th>Status</th>
                <th>Total</th>
                <th>Partial</th>
                <th>Charge</th>
                <th>Sub Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach($parcels as $key=>$value)
            <tr>
                <td>{{$loop->iteration}}</td>
                @php
                $merchant = App\Models\Merchant::find($value->merchantId);
                $agentInfo = App\Models\Agent::find($value->agentId);
                $deliverymanInfo = App\Models\Deliveryman::find($value->deliverymanId);
                @endphp
                <td>{{$merchant->companyName}}</td>
                <td>{{$value->recipientName}}</td>
                <td>{{$value->trackingCode}}</td>
                <td>{{$value->invoiceNo}}</td>
                <td>{{$value->recipientAddress}}</td>
                <td>{{$value->recipientPhone}}</td>
                <td>@if($value->deliverymanId) {{$deliverymanInfo->name}} @endif</td>
                <!-- Modal -->

                <!-- Modal end -->
                <td>@if($value->agentId) {{$agentInfo->name}} @endif</td>
                <!-- Modal -->

                <!-- Modal end -->
                  <td>{{date('F d, Y', strtotime($value->created_at))}}</td>
                <td>{{date('F d, Y', strtotime($value->updated_at))}}</td>
                
                <td><?php    $parceltype = App\Models\Parceltype::where('id',$value->status)->first(); ?>{{@$parceltype->title}}
                </td>
                <td> {{$value->cod}}</td>
                 <td><?php if ($value->partial_pay==null) {
                            $partial=0;
                          } else {
                            $partial=$value->cod-$value->partial_pay;
                          }
                           ?> {{$value->partial_pay}}</td>
                <td> {{(int)$value->deliveryCharge+(int)$value->codCharge}}</td>
                <td> {{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}}</td>

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