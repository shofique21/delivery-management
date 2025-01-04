@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Dashboard')
@section('content')
<section  class="section-padding dashboard-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
      <div class="stats-reportList-inner">
         <div class="row">
             <div class="col-md-12">
                 <h3 class="mb-3">Parcel Overview</h3>
             </div>
         </div>
        <div class="row">
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcel')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$placepercel}}</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">All Parcel</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <!-- col end -->
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/pending')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/pending.png" alt="Pending">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$pendingparcel}}@if($pendingparcel)({{round((@$pendingparcel*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">Pending Parcel</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/picked')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/picked.png" alt="Picked">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$picked}}@if($picked)({{round((@$picked*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">Picked Parcels</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/in-transit')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/in-transit.png" alt="In Transit">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$intransit}}@if($intransit)({{round((@$intransit*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">In Transit Parcels</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/deliverd')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/delivered.png" alt="Delivered">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$deliverd}}@if($deliverd)({{round((@$deliverd*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">Delivered Parcels</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/cancelled')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/cancel.png" alt="Cancelled">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$cancelparcel}}@if($cancelparcel)({{round((@$cancelparcel*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">Cancelled Parcels</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/hold')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/hold.png" alt="Hold">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$totalhold}}@if($totalhold)({{round((@$totalhold*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">Hold Parcels</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('merchant/parcels/return-to-merchant')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/return.png" alt="Return">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$parcelreturn}}@if($parcelreturn)({{round((@$parcelreturn*100)/@$placepercel,2)}}%)
                       @endif</h5>
                      <p class="font-size-16 mt-0 mb-0 text-center">Returned To Merchant</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          
      
          <!-- col end -->
        </div>
      </div>
      <!-- dashboard parcel -->
      <div class="dashboard-payment-info">
           <div class="row">
             <div class="col-md-12">
                 <h3 class="mb-3">Payment Overview</h3>
             </div>
         </div>
        <div class="row">
            <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-sm-3">
                            <div class="avatar text-center">
                                <img src="/icons/collected.png" alt="Collected">
                            </div>
                        </div>
                        <div class="col-12 col-sm-9">
                          <h3 class="fw-bold mt-1 fs-2 text-center">{{round($availabeAmount,2)}}</h3>
                          <p class="font-size-16 mt-0 mb-0 text-center">Available  Amount</p>
                          
                          
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-sm-3">
                            <div class="avatar text-center">
                                <img src="/icons/collected.png" alt="Collected">
                            </div>
                        </div>
                        <div class="col-12 col-sm-9">
                          <h3 class="fw-bold mt-1 fs-2 text-center">{{round($totalamount,2)}}</h3>
                          <p class="font-size-16 mt-0 mb-0 text-center">Collected Amount</p>
                          
                          
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Withdrawal Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <!--<form method="post" action="{{url('merchant/withdrawal')}}">-->
                  <!--    @csrf-->
                  <!--<div class="modal-body">-->
                  <!--  <p>Collected Amount {{round($totalamount,2)}}</p>-->
                  <!--  <p>Withdrawal Amount  {{ round($WithdrawalBlance,2)-$totalWithdrawalBlance }}</p>-->
                  <!--   <input type="hidden" id="" name="Withdrawal" value="{{$WithdrawalBlance}}">-->

                  <!--</div>-->
                  <!--<div class="modal-footer">-->
                  <!--  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                  <!--  @if($WithdrawalBlance > 0)-->
                    <!--<button type="submit" class="btn btn-primary">Withdrawal</button>-->
                  <!--  @endif-->
                  <!--</div>-->
                  <!--</form>-->
                  <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">	
            <form action="{{url('/merchant/withdrawal-request')}}" method="POST" id="myform">
                @csrf
                <button type="submit" class="bulkbutton" onclick="return confirm('Are you want Withdrawal Request?')">Apply</button>
                </form>
               <table id="example777" class="table  table-striped">
                 <thead>
                   <tr>
                <th><input type="checkbox"  id="My-Button"></th>
                   <th>Tracking ID</th>
                    <th>Invoice <br> Number</th>
                   <th>Date</th>
                   <th>Status</th>
                   <th>Rider</th>
                   <th>Total</th>
                   <th>Charge</th>
                   <th>Sub Total</th>
                   <th>Payment Status</th>
                   <th>Note</th>
                   <th>More</th>
                 </tr>
                 </thead>
                <tbody>
                    @php 
                    $dCharge = 0;
                    $pCharge = 0;
                    @endphp
             @foreach($withdrawalParcel as $key=>$value)
                 <tr>
                  <td><input type="checkbox"  value="{{$value->id}}" name="parcel_id[]" form="myform"></td>
                   <td>{{$value->trackingCode}}</td>
                    <td>{{$value->invoiceNo}}</td>
                   <td>{{$value->created_at}}</td>
                   
                  <td>
                    @php
                      $parcelstatus = App\Models\Parceltype::find($value->status);
                   @endphp
                     {{$parcelstatus->title}}
                    </td>
                     <td>
                         @php
                            $deliverymanInfo = App\Models\Deliveryman::find($value->deliverymanId);
                            $dCharge += $value->deliveryCharge+$value->codCharge;
                            $pCharge += $value->cod-($value->deliveryCharge+$value->codCharge);
                          @endphp
                          @if($value->deliverymanId) {{$deliverymanInfo->name}} @else Not Asign @endif
                     </td>
                    <td> {{$value->cod}}</td>
                    <td> {{(int)$value->deliveryCharge+(int)$value->codCharge}}</td>
                    <td> {{(int)$value->cod-(int)($value->deliveryCharge+$value->codCharge)}}</td>
                    <td>@if($value->merchantpayStatus==NULL) NULL @elseif($value->merchantpayStatus==0) Processing @else Paid @endif</td>
                    <td>
                        @php 
                            $parcelnote = App\Models\Parcelnote::where('parcelId',$value->id)->orderBy('id','DESC')->first();
                        @endphp
                        @if(!empty($parcelnote))
                        {{$parcelnote->note}} <br>
                        <small>
                            {{$parcelnote->cnote}}
                        </small>
                        @endif
                    </td>
                   <td>
                    <li>
                      <a href="{{url('merchant/parcel/in-details/'.$value->id)}}" class="btn btn-info"><i class="fa fa-eye"></i></a>
                      @if($value->status ==1)
                      <a href="{{url('merchant/parcel/edit/'.$value->id)}}" class="btn btn-danger"><i class="fa fa-edit"></i></a>
                      @endif
                    </li>
                              
                      <li>
                      <a class="btn btn-primary" a href="{{url('merchant/parcel/invoice/'.$value->id)}}"  title="Invoice"><i class="fas fa-list"></i></a>
                      </li>
                    
                      @if($value->status==1)
                      <li>
                        <form action="{{url('merchant/parcel/cancel')}}" method="post">
                        @csrf
                        <input type="hidden" name="pid" value="{{$value->id}}">
                        <input type="submit" class="btn btn-sm btn-danger" value="cancel">
                        </form>

                   
                      </li>   
                      @endif
                   </td>
                 </tr>
                 @endforeach
                
                 
                </tbody>
                
               </table>
                {{ $withdrawalParcel->links('pagination::bootstrap-4') }}
             </div>
        </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-sm-3">
                            <div class="avatar text-center">
                                <img src="/icons/charge.png" alt="Charge">
                            </div>
                        </div>
                        <div class="col-12 col-sm-9">
                          <h3 class="fw-bold mt-1 fs-2 text-center">{{$deliveryCharge}}</h3>
                          <p class="font-size-16 mt-0 mb-0 text-center">Delivery Charge</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-sm-3">
                            <div class="avatar text-center">
                                <img src="/icons/paid.png" alt="Paid">
                            </div>
                        </div>
                        <div class="col-12 col-sm-9">
                          <h3 class="fw-bold mt-1 fs-2 text-center">{{$merchantPaid}}</h3>
                          <p class="font-size-16 mt-0 mb-0 text-center">Paid Amount</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
            <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-sm-3">
                            <div class="avatar text-center">
                                <img src="/icons/due.png" alt="Due">
                            </div>
                        </div>
                        <div class="col-12 col-sm-9">
                          <h3 class="fw-bold mt-1 fs-2 text-center">{{$merchantUnPaid}}</h3>
                          <p class="font-size-16 mt-0 mb-0 text-center">Unpaid Amount</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>
        
        <div class="row">
          <div class="col-sm-12">
           <div class="card">
             <div class="card-header">
               <h3>Parcel Statistics</h3>
             </div>
             <div class="card-body">
               <canvas id="myChart"></canvas>
             </div>
           </div>
          </div>
        </div>
      </div>
      <!-- dashboard payment -->
      </div>
    </div>
  </div>
</section>
    <!-- Modal
    <div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-labelledby="OpeningModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="OpeningModalTitle">সম্মানিত মার্চেন্ট,</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="mb-0">আপনাদের অবগতির জন্য জানানো যাচ্ছে যে, পবিত্র ঈদুল আজহার আগে ঢাকার বাইরে পার্সেল বুকিং ও পিক-আপের শেষ তারিখ ১৬ জুলাই। আনন্দের সাথে আরও জানাচ্ছি যে, ঢাকার ভেতরে ঈদের দিনেও পার্সেল বুকিং ও ডেলিভারি অব্যাহত থাকবে।</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
       <script type="text/javascript">
        $(window).on('load', function() {
            $('#openModal').modal('show');
        });
    </script>  -->
<script>
   var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'pie',
    margin: 250,

    // The data for our dataset
    data: {
        labels: [@foreach($parceltypes as $parceltype)'{{$parceltype->title}}',@endforeach],
        datasets: [{
            label: 'Parcel Statistics',
            backgroundColor:['#1D2941','#5F45DA','#670A91','#2164af','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
            borderColor:['#1D2941','#5F45DA','#670A91','#2164af','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
             data: [@foreach($parceltypes as $parceltype)
             @php
             $parcelcount = App\Models\Parcel::where(['status'=>$parceltype->id,'merchantId'=>Session::get('merchantId')])->count();
             @endphp {{$parcelcount}}, @endforeach]
        }]
    },

    // Configuration options go here
        options: {
            plugins: {
                title: {
                    display: true,
                    padding: {
                        top: 50,
                        bottom: 50
                    }
                }
            }
        },
});
 </script>
  <script>
        jQuery("#My-Button").click(function() {
        jQuery(':checkbox').each(function() {
          if(this.checked == true) {
            this.checked = false;                        
          } else {
            this.checked = true;                        
          }      
        });
      });
    </script>
@endsection