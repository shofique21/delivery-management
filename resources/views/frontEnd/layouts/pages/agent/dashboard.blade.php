@extends('frontEnd.layouts.pages.agent.agentmaster')
@section('title','Dashboard')
@section('content')
<section  class="section-padding dashboard-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
      <div class="stats-reportList-inner">
        <div class="row">
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <a href="{{url('agent/parcels')}}">
            <div class="stats-reportList bg-dark">
              <div class="stats-per-item">
                <h5>Total Parcel</h5>
                <h3>{{$totalparcel}}</h3>
              </div>
            </div>
            </a>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
              <a href="{{url('agent/parcels')}}">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Total Deliverd</h5>
                <h3>{{$totaldelivery}}</h3>
              </div>
            </div>
            </a>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
              <a href="{{url('agent/parcels')}}">
            <div class="stats-reportList bg-secondary">
              <div class="stats-per-item">
                <h5>Total Hold</h5>
                <h3>{{$totalhold}}</h3>
              </div>
            </div>
            </a>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
              <a href="{{url('agent/parcels')}}">
            <div class="stats-reportList bg-warning">
              <div class="stats-per-item">
                <h5>Total Cancelled</h5>
                <h3>{{$totalcancel}}</h3>
              </div>
            </div>
            </a>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
              <a href="{{url('agent/parcels')}}">
            <div class="stats-reportList bg-info">
              <div class="stats-per-item">
                <h5>Return Pending</h5>
                <h3>{{$returnpendin}}</h3>
              </div>
            </div>
            </a>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
              <a href="{{url('agent/parcels')}}">
            <div class="stats-reportList bg-danger">
              <div class="stats-per-item">
                <h5>Returned To Merchant</h5>
                <h3>{{$returnmerchant}}</h3>
              </div>
            </div>
            </a>
          </div>
          <!-- col end -->
        </div>
      </div>
      <!-- dashboard payment -->
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
      <div class="stats-reportList-inner">
        <div class="row">
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-dark">
              <div class="stats-per-item">
                <h5>Total Cod Amount</h5>
                <h3>{{round($totalAmount,2)}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Collected Cod</h5>
                <h3>{{round($totalColected,2)}}</h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Today Collected Cod</h5>
                <div class="row">
                <div class="col-md-6">
                     <h3>{{$totalTodayColected}}</h3>
                </div>
                <div class="col-md-6">
                     <a href="{{url('agent/transaction')}}" class="text-white">Pay to Accounts </a> 
                </div>
                </div>
               
              </div>
            </div>
          </div>
          <!-- col end -->
          <!--<div class="col-lg-4 colo-md-4 col-sm-4 col-6">-->
          <!--  <div class="stats-reportList bg-info">-->
          <!--    <div class="stats-per-item">-->
          <!--      <h5>Commission Amount</h5>-->
          <!--      <h3>{{$commition}}</h3>-->
          <!--    </div>-->
          <!--  </div>-->
          <!--</div>-->
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-success">
              <div class="stats-per-item">
                <h5>Paid Amount</h5>
                <h3>{{$paidAmount}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <div class="col-lg-4 colo-md-4 col-sm-4 col-6">
            <div class="stats-reportList bg-danger">
              <div class="stats-per-item">
                <h5>Due Amount</h5>
                <h3>{{$totalColected-($commition+$paidAmount)}}</h3>
              </div>
            </div>
          </div>
          <!-- col end -->
          <!--<div class="col-lg-4 colo-md-4 col-sm-4 col-6">-->
          <!--  <div class="stats-reportList bg-dark">-->
          <!--    <div class="stats-per-item">-->
          <!--      <h5> <a href="{{url('agent/transaction')}}">Create New Transaction</a>  </h5>-->
          <!--      <h3>{{$transaction}}</h3>-->
          <!--    </div>-->
          <!--  </div>-->
          <!--</div>-->
          <!-- col end -->
        </div>
      </div>
      <!-- dashboard payment -->
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
            backgroundColor:['#1D2941','#5F45DA','#670A91','#096709','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
            borderColor:['#1D2941','#5F45DA','#670A91','#096709','#FFAC0E','#AAB809','#2094A0','#9A8309','#C21010'],
             data: [@foreach($parceltypes as $parceltype)
             @php
             $parcelcount = App\Models\Parcel::where(['status'=>$parceltype->id,'agentId'=>Session::get('agentId')])->count();
             @endphp {{$parcelcount}}, @endforeach]
        }]
    },

    // Configuration options go here
    options: {}
});
 </script>
@endsection