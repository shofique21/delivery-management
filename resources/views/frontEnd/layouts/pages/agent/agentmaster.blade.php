<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Flingex | @yield('title','Agent DashBoard')</title>
    <!-- Meta tag Keywords -->
     <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, minimum-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta tag Keywords -->
    <link rel="shortcut icon" type="image/jpg" href="https://flingex.com/public/frontEnd/images/icon/favicon-32x32.png"/>
    <!-- Custom-Files -->
    <link rel="stylesheet" href="{{asset('frontEnd')}}/css/bootstrap4.min.css">
    <!-- Bootstrap-Core-CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- flaticon -->
    <link rel="stylesheet" href="{{asset('frontEnd')}}/css/merchant.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{asset('frontEnd')}}/css/swiper-menu.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{asset('backEnd/')}}/dist/css/toastr.min.css">
    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <!-- Style-CSS -->
     <link href="{{asset('frontEnd')}}/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- Font-Awesome-Icons-CSS -->
    <!-- select2 -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/select2/css/select2.min.css">
  <!-- select2 -->
    <!-- //Custom-Files -->
    <!--<script src="{{asset('public/frontEnd/')}}/js/jquery_3.4.1_jquery.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <style>
        
        @media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}

    .hide-desktop{ display: none;}
    @media only screen and (max-width: 991px){
        .hide-desktop{ display: block;}
    }
    span.relative.z-0.inline-flex.shadow-sm.rounded-md {
    display: none;
}
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light d-none">
  <a href="{{url('/agent/dashboard')}}">
                            Dashboard
                        </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
     
      
     
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Parcel
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

          
                        <a href="{{url('agent/parcels-request')}}" class="dropdown-item">
                            
                             Parcel Request(<?php echo App\Models\Parcel::where('agentId',Session::get('agentId'))->where('agentAprove',null)->where('archive',1)->count() ?>)
                        </a>
                   
                        <a href="{{url('agent/parcels')}}" class="dropdown-item">
                            
                            All Parcel (<?php echo App\Models\Parcel::where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                        <a href="{{url('agent/parcels/picked')}}" class="dropdown-item">
                           
                            Delivery Pending (<?php echo App\Models\Parcel::where('status',2)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                   
                        <a href="{{url('agent/parcels/in-transit')}}" class="dropdown-item">
                           
                            In Transit (<?php echo App\Models\Parcel::where('status',3)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                        <a href="{{url('agent/parcels/hold')}}" class="dropdown-item">
                            
                            Hold (<?php echo App\Models\Parcel::where('status',5)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                   
                        <a href="{{url('agent/parcels/return-pending')}}" class="dropdown-item">
                            
                            Delivered  (<?php echo App\Models\Parcel::where('status',4)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                        <a href="{{url('agent/parcels/return-pending')}}" class="dropdown-item">
                           
                            Return Pending (<?php echo App\Models\Parcel::where('status',6)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                       
                   
                        <a href="{{url('agent/parcels/return-to-hub')}}" class="dropdown-item">
                           
                            Returned to Hub  (<?php echo App\Models\Parcel::where('status',7)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                        <a href="{{url('agent/hub-request')}}" class="dropdown-item">
                           
                            Returned to centerl Hub  (<?php echo App\Models\Parcel::where('status',10)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                        <a href="{{url('agent/parcels/return-to-merchant')}}" class="dropdown-item">
                          
                            Returned To Merchant  (<?php echo App\Models\Parcel::where('status',8)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    
                   
                    
        </div>
      </li>
       <li class="nav-item">
        <a href="{{url('agent/pickup')}}">Pickup Request</a>
      </li>
      <li class="nav-item">
        <a href="{{url('agent/transaction')}}">Transaction</a>
      </li>
       <li class="nav-item ">
       <a href="{{url('agent/transaction/deliveryman')}}"> Transaction Request </a>
      </li>
      <li class="nav-item">
           <a href="{{url('agent/report')}}"> Report</a>
      </li>
      <li class="nav-item"><a href="{{url('agent/logout')}}"> Logout  </a></li>
    </ul>
  </div>
</nav>
    @php
        $agentInfo = App\Models\Agent::find(Session::get('agentId'));
    @endphp
     <section class="mobile-menu no-print d-none">
        <div class="swipe-menu default-theme">
            <div class="postyourad">
                <a href="{{url('merchant/dashboard')}}">
                  @foreach($whitelogo as $key=>$value)
                    <img src="{{asset($value->image
                    )}}" alt="Your logo"/>
                    @endforeach
                </a>
                 <a  href="{{url('agent/dashboard')}}" class="mobile-username">{{@$agentInfo->names}}</a>
            </div>
        <!--Navigation Icon-->
            <div class="nav-icon">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="codehim-nav">
                  <div class="side-list">
                <ul>
                    <li>
                        <a href="{{url('/agent/dashboard')}}">
                            <div class="list-icon"><i class="fa fa-home"></i></div>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/picked')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Delivery Pending <?php echo App\Models\Parcel::where('status','2')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/in-transit')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            In Transit <?php echo App\Models\Parcel::where('status','3')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/hold')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Hold <?php echo App\Models\Parcel::where('status',5)->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                   
                       
                        <a href="{{url('agent/parcels/return-pending')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Return Pending <?php echo App\Models\Parcel::where('status','6')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/deliverd')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Delivered  <?php echo App\Models\Parcel::where('status','4')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/return-to-hub')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Returned to Hub  <?php echo App\Models\Parcel::where('status','7')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/hub-request')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Returned to centerl Hub  <?php echo App\Models\Parcel::where('status','10')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{url('agent/parcels/return-to-merchant')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Returned To Merchant  <?php echo App\Models\Parcel::where('status','8')->where('agentId',Session::get('agentId'))->where('archive',1)->count() ?>
                        </a>
                    </li>
                   
              @if(Session::get('agentId') =='10')
              <li>
                            <a href="{{url('agent/hub-request')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Hub Request
                            </a>
                        </li>
                        @endif
               <li>
                            <a href="{{url('agent/pickup')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Pickup Request
                            </a>
                        </li>
              <li>
                            <a href="{{url('agent/transaction')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Transaction
                            </a>
                        </li>
                        <li>
                            <a href="{{url('agent/transaction/deliveryman')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Transaction Request
                            </a>
                        </li>
               <li>
                        <a href="{{url('agent/report')}}">
                            <div class="list-icon"><i class="fa fa-bus"></i></div>
                            Report
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                            Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/logout')}}">
                            <div class="list-icon"><i class="fa fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
            <!--//Tab-->
            </nav>
        </div>
    </section>
    <!-- mobile menu end -->
    <section class="main-area elevation-4 user-panel ">
      <div class="dash-sidebar no-print d-nonex">
            <div class="sidebar-inner">
            <div class="profile-inner">
                <div class="profile-pic">
                    <a href="#"><img src="{{asset('public/frontEnd')}}/images/avator.png" alt=""></a>
                </div>
                <div class="profile-id">
                    <p>{{$agentInfo->name}}: {{$agentInfo->id}}</p>
                </div>
            </div>
            <div class="side-list">
                <ul>
                    <li>
                        <a href="{{url('/agent/dashboard')}}">
                            <div class="list-icon"><i class="fa fa-home"></i></div>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels-request')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                             Parcel Request(<?php echo App\Models\Parcel::where('agentId',Session::get('agentId'))->where('agentAprove',null)->where('archive',1)->count() ?>)
                            <br> <small class="bg-danger">Please Accept Parcel</small>
                        </a>
                        
                    </li>
                    <li>
                        <a href="{{url('agent/parcels')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            All Parcel (<?php echo App\Models\Parcel::where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{url('agent/parcels/picked')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Delivery Pending (<?php echo App\Models\Parcel::where('status',2)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/in-transit')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            In Transit (<?php echo App\Models\Parcel::where('status',3)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/hold')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Hold (<?php echo App\Models\Parcel::where('status',5)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    
                       
                    <li>
                        <a href="{{url('agent/parcels/return-pending')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Return Pending (<?php echo App\Models\Parcel::where('status',6)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/deliverd')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Delivered  (<?php echo App\Models\Parcel::where('status',4)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/parcels/return-to-hub')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Returned to Hub  (<?php echo App\Models\Parcel::where('status',7)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/hub-request')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Returned to centerl Hub  (<?php echo App\Models\Parcel::where('status',10)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{url('agent/parcels/return-to-merchant')}}">
                            <div class="list-icon"><i class="fa fa-car"></i></div>
                            Returned To Merchant  (<?php echo App\Models\Parcel::where('status',8)->where('agentId',Session::get('agentId'))->where('agentAprove',1)->where('archive',1)->count() ?>)
                        </a>
                    </li>
                   
                   
               
           @if(Session::get('agentId') ==10)
              <li>
                            <a href="{{url('agent/hub-request')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Hub Request
                            </a>
                        </li>
                        @endif
              
              <li>
                            <a href="{{url('agent/pickup')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Pickup Request
                            </a>
                        </li>
              <li>
                            <a href="{{url('agent/transaction')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Transaction
                            </a>
                        </li>
                        <li>
                            <a href="{{url('agent/transaction/deliveryman')}}">
                                <div class="list-icon"><i class="fa fa-bus"></i></div>
                                Transaction Request
                            </a>
                        </li>
               <li>
                        <a href="{{url('agent/report')}}">
                            <div class="list-icon"><i class="fa fa-bus"></i></div>
                            Report
                        </a>
                    </li>
                      <li>
                        <a href="{{url('agent/deliveryman/asign/report')}}">
                            <div class="list-icon"><i class="fa fa-bus"></i></div>
                            Asign Delivery Man
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                            Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{url('agent/logout')}}">
                            <div class="list-icon"><i class="fa fa-sign-out-alt"></i></div>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
            </div>
        </div>
        <!-- Sidebar End -->
        <div class="dashboard-body">
            <div class="heading-bar no-print">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pik-inner">
                            <ul>
                                <li>
                                    <div class="dash-logo">
                                        @foreach($whitelogo as $key=>$value)
                                        <a href="{{url('merchant/dashboard')}}"><img src="{{asset($value->image)}}" alt="" style="margin-top: 0"></a>
                                        @endforeach
                                    </div>
                                    
                                </li>
                                 <li class="profile-area">
                                    <div class="profile ml-auto">
                                        <a class="" ><img src="{{asset('public/frontEnd')}}/images/avator.png" alt="" >
                                                    
                                        </a>
                                        <ul>
                                            <li><a href="#">Setting</a></li>
                                            <li><a href="{{url('agent/logout')}}">Logout</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                       <?php
                     $deliverymen = App\Models\Deliveryman::where(['status'=>1,'jobstatus'=>1])->where('agentId',Session::get('agentId'))->orderBy('id','DESC')->get();
?>               
                      <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="rider" id="rider" class="form-control select2">
                                    <option value="">Select One Deliverymen</option>
                                    @foreach($deliverymen as $men)
                                    <option value="{{$men->id}}">{{$men->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <button>Submit</button> -->
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="text" id="dbercode" name="dtrackid"
                                    placeholder="Enter Bercode..." search>
                                <!-- <button>Submit</button> -->
    
                            </div>
                            <!-- </form> -->
                            <div class="col-md-8">
                                <div class="">
                                    <ul class="row">
                                    <!--    <li>-->
                                    <!--        <div class="track-area">-->
                                    <!--            <form action="{{url('/agent/parcel/track')}}" method="POST">-->
                                    <!--    @csrf-->
                                    <!--    <input class="form-control" type="text" name="trackid" placeholder="Search your track number..." search>-->
                                    <!--   <button>Submit</button>-->
                                    <!--</form>-->
                                    <!--        </div>-->
                                            
                                    <!--    </li>-->
                                        <li class="col-4">
                                            <div class="track-area">
                                                <select name="dliveid" id="dliveid" class="form-control select2">
                                                    <option value="">Select One</option>
                                                    @foreach($deliverymen as $men)
                                                    <option value="{{$men->id}}">{{$men->name}}</option>
                                                    @endforeach
                                                </select>
                                                <!-- <button>Submit</button> -->
                                            </div>
        
                                        </li>
                                        <li class="col-4">
                                            <div class="track-area">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="a">Accept</option>
                                                    <option value="3">In Transit</option>
                                                    <option value="5">Hold</option>
                                                    <option value="4">Delivered</option>
                                                    <option value="6">Return Pending</option>
                                                    <option value="7">Returned To Hub</option>
                                                    <option value="10">Returned To Central Hub</option>
                                                   
                                                </select>
                                                <!-- <button>Submit</button> -->
                                            </div>
                                        </li>
                                        <li class="col-4">
                                            <div class="track-area">
                                                <input class="form-control" type="text" id="bercode" name="trackid"
                                                    placeholder="Enter Bercode..." search>
                                                <!-- <button>Submit</button> -->
                                               
                                            </div>
                                            <!-- </form> -->
                                        </li>
                                        <!--<li class="profile-area col">-->
                                        <!--    <div class="profile">-->
                                        <!--        <a class="" ><img src="{{asset('public/frontEnd')}}/images/avator.png" alt="" >-->
                                                            
                                        <!--        </a>-->
                                        <!--        <ul>-->
                                        <!--            <li><a href="#">Setting</a></li>-->
                                        <!--            <li><a href="{{url('agent/logout')}}">Logout</a></li>-->
                                        <!--        </ul>-->
                                        <!--    </div>-->
                                        <!--</li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              <div class="hide-desktop">
                  <div class="row mt-2 m-1">
                      <div class="col-6">
                            <select name="status" id="status" class="form-control">
                                <option value="a">Accept</option>
                                @foreach($parceltypes as $parceltype)
                                <option value="{{$parceltype->id}}">{{$parceltype->title}}</option>
                                @endforeach
                            </select>
                      </div>
                      <div class="col-6">
                          <input class="form-control" type="text" id="bercode" name="trackid" placeholder="Enter Bercode..."
                            search>
                      </div>
                  </div>
              </div>
            <div class="main-body">
                <div class="col-sm-12">
                    @yield('content')
                </div>
            </div>
            <!-- Column End-->
        </div>
    </section>

        <!--Next Day Pick Modal end -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="{{asset('frontEnd/')}}/js/bootstrap4.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{asset('frontEnd/')}}/js/swiper-menu.js" ></script>
  <script src="{{asset('backEnd/')}}/dist/js/toastr.min.js"></script>
  {!! Toastr::render() !!}
  <!-- Datatable -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="{{asset('backEnd/')}}/plugins/datatables/jquery.dataTables.js"></script>
  <script src="{{asset('backEnd/')}}/plugins/datatables/dataTables.bootstrap4.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js "></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js "></script>
  <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
  <script src="{{asset('backEnd/')}}/plugins/select2/js/select2.full.min.js"></script>
  @yield('js')
  <script>
        function calculate_result(){
         $.ajax({
           type:"GET",
           url:"{{url('cost/calculate/result')}}",
                 dataType: "html",
                 success: function(deliverycharge){
                   $('.calculate_result').html(deliverycharge)
                 }
              });
          }
        $('.calculate').on('keyup paste click',function(){
            var cod = $('.cod').val();
            var weight = $('.weight').val();
             if(cod,weight){
                  $.ajax({
                   cache: false,
                   type:"GET",
                   url:"{{url('cost/calculate')}}/"+cod+'/'+weight,
                   dataType: "json",
                   success: function(deliverycharge){
                       return calculate_result();
                }
              });
            }
        });
    </script>
    
    <!-- barcode rider  -->
    <script>
    $(document).ready(function() {
        $('#bercode').on('keyup', function(e) {
            
            e.preventDefault();
            if (e.which == 13) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var ber = $("#bercode").val();
                var st = $("#status").val();
                var dliverid = $("#dliveid").val();
                console.log(ber);
                // var weight = $('.weight').val();
                if (ber) {
                    $.ajax({
                        cache: false,
                        type: "POST",
                        url: "{{url('/agent/parcel/track/')}}",
                        dataType: "json",
                        data: {
                            trackid: ber,
                            status:st,
                            dliveid: dliverid,
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.success == 1) {
                                $("#bercode").val(null);
                                toastr.success('<h2> Parcel status update successfully</h2>');
                                
                            } else {
                                $("#bercode").val(null);
                                toastr.error('<h2>Please Accept Your Parcel !!</h2>');
                                
                            }


                        }
                    });
                }
            }
            
        });
    });
    </script>
    <!-- barcode rider start -->
    <script>
    $(document).ready(function() {
        $('#dbercode').on('keyup', function(e) {

            e.preventDefault();
            if (e.which == 13) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var ber = $("#dbercode").val();
                var st = $("#rider").val();
                console.log(st);

                if (ber) {
                    $.ajax({
                        cache: false,
                        type: "POST",
                        url: "{{url('/agent/parcel/rider/')}}",
                        dataType: "json",
                        data: {
                            dtrackid: ber,
                            rider: st
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.success == 1) {
                                $("#dbercode").val(null);
                                toastr.success(
                                    '<h3>A deliveryman asign successfully!</h3>');

                            } else {
                                $("#dbercode").val(null);
                                toastr.error(
                                    '<h3>Please Accept Your Parcel !!</h3>');

                            }


                        }
                    });
                }
            }

        });
    });
    </script>
    <!-- barcode rider end -->
    <script>
      flatpickr(".flatDate", {});
    </script>
      <script>
       $(document).ready(function() {
          $('#example').DataTable( {
              dom: 'Bfrtip',
               stateSave: true,
              buttons: [
                  {
                      extend: 'copy',
                      text: 'Copy',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                      }
                  },
                  {
                      extend: 'csv',
                      text: 'Csv',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                      }
                  },
                  
                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8]
                      }
                  },
                  {
                      extend: 'print',
                      text: 'Print all',
                      exportOptions: {
                          modifier: {
                              selected: null
                          }
                      }
                  },
                  {
                      extend: 'colvis',
                  },
                  
              ],
              select: true
          } );
          
           table.buttons().container()
              .appendTo( '#example_wrapper .col-md-6:eq(0)' );
      });
</script>
<script>
    
    $(document).ready(function() {
    $('#exampled').DataTable( {
       
        dom: 'Bfrtip',
         stateSave: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
<script>

    $(function () {
          $('.select2').select2();
     flatpickr("#flatpicker", {
      minDate:"today",
     });
    })
</script>

</body>

</html>
