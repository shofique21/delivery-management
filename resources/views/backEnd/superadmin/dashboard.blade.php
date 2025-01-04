@extends('backEnd.layouts.master')
@section('title','Super Admin Dashboard')
@section('content')
 <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark text-right">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v2</li>
          </ol>
        </div>
        <!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
<!-- Main content -->
  <section class="section-padding dashboard-content">
    <div class="container">
     <div class="box-content">
      <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card custom-card dashboard-body">
                  <div class="col-sm-12">
                    <div class="manage-button">
                      <div class="body-title">
                        <h5 class="text-center">Parcel Overall Status</h5>
                      </div>
                    </div>
                  </div>
                <div class="card-body">
                    <div class="row">
                         <div class="col-lg-3 colo-md-3 col-sm-4 col-6" style="padding-right: 15px; padding-left: 15px;">
            <a class="rounded card mb-3 d-block" href="{{url('/editor/parcel/all-parcel')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img style="width:40px;" src="{{asset('/icons/all.png')}}" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">All Parcel</h5>
                      <p class="font-size-26 mt-0 mb-0 text-center">@php echo App\Models\Parcel::where('archive',1)->count();  @endphp </p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
                      @foreach($parceltypes as $key=>$value)
                      @php
                        $parcelcount = App\Models\Parcel::where('archive',1)->where('status',$value->id)->count();
                      @endphp
                       <div class="col-lg-3 colo-md-3 col-sm-4 col-6" style="padding-right: 15px; padding-left: 15px;">
            <a class="rounded card mb-3 d-block" href="{{url('editor/parcel/'.$value->slug)}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img style="width:40px;" src="{{asset('/icons/'.$value->images)}}" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">{{$value->title}}</h5>
                      <p class="font-size-26 mt-0 mb-0 text-center">{{$parcelcount}}</p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
                        <!-- col end -->
                        @endforeach
                        
             <div class="col-lg-3 colo-md-3 col-sm-4 col-6" style="padding-right: 15px; padding-left: 15px;">
            <a class="rounded card mb-3 d-block" href="{{url('/editor/parcel/archive-parcel')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img style="width:40px;" src="{{asset('/icons/delivered.png')}}" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">Archive Parcel</h5>
                      <p class="font-size-26 mt-0 mb-0 text-center">@php echo App\Models\Parcel::where('archive',2)->count();  @endphp </p>
                    </div>
                  </div>
              </div>
            </a>
          </div>
           <div class="col-lg-3 colo-md-3 col-sm-4 col-6" style="padding-right: 15px; padding-left: 15px;">
            <a class="rounded card mb-3 d-block" href="{{url('/editor/parcel/return-to-hub/central')}}">
              <div class="card-body">
                  <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="avatar text-center">
                            <img style="width:40px; margin: 18px;" src="/icons/return.png" alt="All">
                        </div>
                    </div>
                    <div class="col-12 col-sm-9">
                      <h5 class="fw-bold mt-1 fs-2 text-center">Return to Central Hub</h5>
                      <h4 class="mt-0 mb-0 text-center">@php echo App\Models\Parcel::where('status',7)->where('hubaprove',1)->where('agentId',10)->get()->count();  @endphp </h4>
                    </div>
                  </div>
              </div>
            </a>
          </div>
                    </div>
                </div>
              </div>
          </div>
          <!-- main col end -->
           <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card custom-card dashboard-body">
                  <div class="col-sm-12">
                    <div class="manage-button">
                      <div class="body-title text-center">
                        <h5>Payment Overall Status</h5>
                      </div>
                    </div>
                  </div>
                <div class="card-body">
                    <div class="row ">
                    
                <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row ">
                       
                        <div class="col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Total Cod Amount</h5>
                          <p class="font-size-16 mt-0 mb-0 text-center"><?php echo round($totalcod,2); ?></p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
             <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        
                        <div class=" col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Total Delivery Charge</h5>
                         <p class="font-size-16 mt-0 mb-0 text-center">{{$totaldc}}</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
                      <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        
                        <div class="col-12 col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Current Month delivery charge </h5>
                         <p class="font-size-16 mt-0 mb-0 text-center">{{$currentMdC}}</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
                      <!-- col end -->
                   
                    <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        
                        <div class="col-12 col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Current Month Rider Commission</h5>
                          <p class="font-size-16 mt-0 mb-0 text-center">{{$monthlydeC}}</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
                
                      <!-- col end -->
                    </div>
                <div class="row">
                    
                <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        
                        <div class="col-12 col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Total Delivery Amount</h5>
                          <p class="font-size-16 mt-0 mb-0 text-center"><?php echo round(App\Models\Parcel::where('status',4)->sum('cod'),2); ?></p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
             <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        
                        <div class="col-12 col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Merchant Due Amount</h5>
                          <a href="{{url('editor/merchant-due-report')}}"><p class="font-size-16 mt-0 mb-0 text-center">{{$merchantsdue}}</p></a> 
                        </div>
                      </div>
                  </div>
                </div>
            </div>
                      <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        
                        <div class="col-12 col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Merchant Paid Amount </h5>
                         <p class="font-size-16 mt-0 mb-0 text-center">{{$merchantspaid}}</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
                      <!-- col end -->
                   
                    <div class="col-lg-3 colo-md-3 col-sm-3 col-6" style="padding-right: 15px; padding-left: 15px;">
                <div class="rounded card mb-3">
                  <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-sm-12">
                          <h5 class="fw-bold mt-1 fs-2 text-center">Today Merchant Payment</h5>
                          <p class="font-size-16 mt-0 mb-0 text-center">{{$todaymerchantspaid}}</p>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
                
                      <!-- col end -->
                    </div>
                </div>
              </div>
          </div>
          <!-- main col end -->
          <!-- <div class="col-sm-12 col-md-12 col-lg-12">-->
          <!--  <div class="card custom-card dashboard-body">-->
          <!--        <div class="col-sm-12">-->
          <!--          <div class="manage-button">-->
          <!--            <div class="body-title">-->
          <!--              <h5>Overall Status</h5>-->
          <!--            </div>-->
          <!--          </div>-->
          <!--        </div>-->
          <!--    </div>-->
          <!--</div>-->
          <!-- main col end -->
       </div>
       <div class="row">
        <div class="col-sm-12">
         <div class="card">
           <div class="card-header">
             <h3 class="text-center">Parcel Statistics</h3>
           </div>
           <div class="card-body">
             <canvas id="myChart"></canvas>
           </div>
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
             $parcelcount = App\Models\Parcel::where('status',$parceltype->id)->count();
             @endphp {{$parcelcount}}, @endforeach]
        }]
    },

    // Configuration options go here
    options: {}
});
 </script>
@endsection