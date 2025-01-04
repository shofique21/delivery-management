@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title','Dashboard')
@section('content')
<section  class="section-padding dashboard-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
      <div class="stats-reportList-inner">
        @if(Session::get('jobstatus')==2)
        <div class="row">
          <div class="col-md-12">
              <h5 class="mb-3 text-uppercase fw-bold text-center">Parcel Details</h5>
          </div>
     </div>
     <div class="row">
       <div class="col-lg-6 colo-md-6 col-sm-6 col-6">
         <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels/pending')}}">
           <div class="card-body">
               <div class="row">
                 <div class="col-12 col-sm-3">
                     <div class="avatar text-center">
                         <img src="/icons/all.png" alt="All">
                     </div>
                 </div>
                 <div class="col-12 col-sm-9">
                   <h3 class="fw-bold mt-1 fs-2 text-center">{{$totalPanding}}</h3>
                   <p class="font-size-16 mt-0 mb-0 text-center"> Pending</p>
                 </div>
               </div>
           </div>
         </a>
       </div>
       <div class="col-lg-6 colo-md-6 col-sm-6 col-6">
        <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels/picked')}}">
          <div class="card-body">
              <div class="row">
                <div class="col-12 col-sm-3">
                    <div class="avatar text-center">
                        <img src="/icons/all.png" alt="All">
                    </div>
                </div>
                <div class="col-12 col-sm-9">
                  <h3 class="fw-bold mt-1 fs-2 text-center">{{$totalPicked}}</h3>
                  <p class="font-size-16 mt-0 mb-0 text-center">Picked</p>
                </div>
              </div>
          </div>
        </a>
      </div>
      
       {{-- div tag for pickupman --}}
      </div>
               @else

               <div class="row">
                <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
                  <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels/pending')}}">
                    <div class="card-body">
                        <div class="row">
                          <div class="col-12 col-sm-3">
                              <div class="avatar text-center">
                                  <img src="/icons/all.png" alt="All">
                              </div>
                          </div>
                          <div class="col-12 col-sm-9">
                            <h3 class="fw-bold mt-1 fs-2 text-center">{{$totalPanding}}</h3>
                            <p class="font-size-16 mt-0 mb-0 text-center">Delivery Pending</p>
                          </div>
                        </div>
                    </div>
                  </a>
                </div>
                
          
          
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels/hold')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h3 class="fw-bold mt-1 fs-2 text-center">{{$totalhold}}</h3>
                      <p class="font-size-16 mt-0 mb-0 text-center">Hold to HUB</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
        
            <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels/return-to-hub')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h3 class="fw-bold mt-1 fs-2 text-center">{{$returntohub}}</h3>
                      <p class="font-size-16 mt-0 mb-0 text-center">Returned to HUB</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
           <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h3 class="fw-bold mt-1 fs-2 text-center">{{$totaldelivery}}</h3>
                      <p class="font-size-16 mt-0 mb-0 text-center">Delivered</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
        
          
        </div>
        @endif
        <div class="row">
             <div class="col-md-12">
                 <h5 class="mb-3 text-uppercase fw-bold text-center mt-3">Payment Details</h5>
             </div>
        </div>
        <div class="row">
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/parcels/deliverd')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h3 class="fw-bold mt-1 fs-2 text-center">{{round($Collected,2)}} </h3>
                      <p class="font-size-16 mt-0 mb-0 text-center">Today Collected Amount</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="#">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    @php
                    $deliverymanInfo = App\Models\Deliveryman::find(Session::get('deliverymanId'));
                    @endphp
                    <div class="col-12 col-sm-9">
                      <h3 class="fw-bold mt-1 fs-2 text-center"> {{@$deliverymanInfo->commission_amount}} tk</h3>
                      <p class="font-size-16 mt-0 mb-0 text-center">My Commission</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/transaction')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h3 class="fw-bold mt-1 fs-2 text-center">{{round($transactionAmount,2)}}</h3>
                      <p class="font-size-16 mt-0 mb-0 text-center">Transaction with HUB</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
          
          <div class="col-lg-3 colo-md-3 col-sm-3 col-6">
            <a class="rounded card mb-3 d-block" href="{{url('/deliveryman/commissionhistory')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img src="/icons/all.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <img src="/icons/collected.png" style="max-height: 35px; margin: 0 auto 5px; display: block;">
                      <p class="font-size-16 mt-0 mb-0 text-center">Commission History</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
        </div>
       
      </div>
      <!-- dashboard payment -->
      </div>
    </div>
    @if(Session::get('jobstatus')!=2)
    <div class="row">
    <div class="col-sm-12">
     <div class="card">
       <div class="card-header">
         <h5 class="text-uppercase text-center fw-bold">Parcel Statistics</h5>
       </div>
       <div class="card-body">
         <canvas id="myChart"></canvas>
       </div>
     </div>
    </div>
  </div>
  @endif
  </div>
</section>
<script>
   var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'pie',

    // The data for our dataset
    data: {
        labels: [@foreach($parceltypes as $parceltype)'{{$parceltype->title}}',@endforeach],
        datasets: [{
            label: 'Parcel Statistics',
            backgroundColor:['#1D2941','#5F45DA','#670A91','#2164af','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
            borderColor:['#1D2941','#5F45DA','#670A91','#2164af','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
             data: [@foreach($parceltypes as $parceltype)
             @php
             $parcelcount = App\Models\Parcel::where(['status'=>$parceltype->id,'deliverymanId'=>Session::get('deliverymanId')])->count();
             @endphp {{$parcelcount}}, @endforeach]
        }]
    },

    // Configuration options go here
    options: {}
});
 </script>
@endsection