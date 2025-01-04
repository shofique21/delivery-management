<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Flingex || @yield('title','Dashbaord')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Font Awesome -->
   <link rel="icon" type="image/png" sizes="96x96" href="{{asset('frontEnd/')}}/images/icon/apple-icon-57x57.png">
    <!-- fabeicon css -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/fontawesome-free/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@300;400;500;700&display=swap" rel="stylesheet">
  <!-- Ionicons -->
  <!--<link rel="stylesheet" href="{{asset('public/backEnd/')}}/plugins/code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">-->
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/summernote/summernote-bs4.css">
  <!-- select2 -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/select2/css/select2.min.css">
  <!-- select2 -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/owlcarousel/owl.carousel.css">
  <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/owlcarousel/owl.theme.default.min.css">
  <!-- owl.theme.default.min -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- flatpickr -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/dist/css/toastr.min.css">
  <!-- datatable -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <!-- custom css -->
  <link rel="stylesheet" href="{{asset('backEnd/')}}/dist/css/custom.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <!--<script src="https://cdn.jsdelivr.net/npm/excellentexport@3.6.0/dist/excellentexport.min.js"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.8.0/JsBarcode.all.js"></script>
  <style>
    body{
        font-family: 'Saira Condensed', sans-serif; 
        /*font-size: 1.2rem; letter-spacing: .4px;*/
    }

    .hide-desktop{ display: none;}
    @media only screen and (max-width: 991px){
        ul.action_buttons {
            margin: 0;
            padding: 0;
        }
        .box-content {
            padding: 2px;
        }
        .content-wrapper > .content {
            padding: 0 !important;
        }
        div#datatable {
            padding: 3px;
        }
        input.form-control {
            margin-bottom: 3px;
        }
        button.btn.btn-success {
            width: 100%;
        }
        .manage-button {
            margin-bottom: 10px;
            padding-bottom: 1px;
        }
        .hide-desktop{ display: block;}
    }
  </style>
  @yield('extracss')
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse" >
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li>
    <a href="{{url('/superadmin/dashboard')}}" class="nav-link">Dashboard </a>
      </li>
     
      <!-- nav item end -->
      
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/clear-cache" target=""  class="nav-link"><i class="fa fa-box"></i> Clear-cache</a>
      </li>
      <!-- nav item end -->
       <li class="nav-item  d-sm-inline-block p-2" style="margin-left:250px;">
                    <form action="{{url('editor/marchant-percel')}}" method="post">
               @csrf
                    <input class="form-control" type="text" id="" name="phone" placeholder="Enter Parcel phone number..."
                        >
            </form>
            </li>
			
		

    </ul>

    
    
            
<!--<li class="nav-item">-->
<!--                <a href="" class="nav-link">-->
<!--                  <i class="fa fa-comments"></i>-->
<!--                                      <p>Complain</p>-->
<!--                </a>-->
<!--              </li>-->

      <ul class="navbar-nav ml-auto">
          <li class="nav-item has-treeview bg-info">
           <a class="nav-link anchor text-white" href="/editor/parcel/insitedhaka-parcel" title="Check Insite Dhaka">
               <b style="  color: red;">ISD</b>
                <sup class="small-font fw-bold"><?php echo App\Models\Parcel::whereDate('created_at', \Carbon::now()->subDays(3))->where('status','!=',4)->where('status','!=',8)->where('status','!=',9)->where('orderType',6)->count(); ?></sup>
          
           </a>
        </li>
        <li class="nav-item has-treeview bg-dark">
           <a class="nav-link anchor text-white" href="/editor/parcel/outsitedhaka-parcel" title="Check Outsite Dhaka">
               <b style="  color: red;">OSD</b>
                <sup class="small-font fw-bold"><?php echo App\Models\Parcel::whereDate('created_at', \Carbon::now()->subDays(5))->where('status','!=',4)->where('status','!=',8)->where('status','!=',9)->where('orderType',5)->count(); ?></sup>
          
           </a>
        </li>
          
        <li class="nav-item has-treeview bg-success">
           <a class="nav-link anchor text-white" href="/editor/merchant/merchantComplain" title="Check Pickup Request">
           <i class="fa fa-comments"></i> <sup class="small-font fw-bold"><?php echo App\Models\Complain::where('status',1)->whereDate('created_at', date('Y-m-d'))->count(); ?></sup>
          </a>
        </li>
         <li class="nav-item has-treeview bg-danger">
           <a class="nav-link anchor text-white" href="/editor/parcel/pickup-request" title="Check Pickup Request">
          <i class="fas fa-truck-pickup"></i><sup class="small-font fw-bold"><?php echo App\Models\Pickup::where('status',0)->where('date', date('Y-m-d'))->count(); ?></sup>
          </a>
        </li>
        
       <!-- nav item end -->
        
       <!-- nav item end -->
        <li class="nav-item has-treeview">
          <a href="{{url('password/change')}}" class="nav-link" title="Change Password">
           <i class="fas fa-key"></i>
          </a>
        </li>
        <!-- nav item end -->
        <li class="nav-item has-treeview">
            <a href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
             <i class="fas fa-sign-out-alt"></i>
             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
             </form>
            </a>
       </li>
       <!-- nav item end -->
    </ul>
  </nav>
          <nav class=" navbar navbar-expand navbar-white ">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>

            </ul>
            <ul class="navbar-nav">
                 <li class="nav-item d-none d-sm-inline-block mr-1 pl-2" style="width:200px;">
                   <select name="delivermanId" id="delivermanId" class="form-control select2" >
                        <option value="">Select Deliveryman</option>
                        @foreach($deliverymen as $men)
                        <option value="{{$men->id}}">{{$men->name}}</option>
                        @endforeach
                    </select>
                </li>
                <li class="nav-item d-none d-sm-inline-block mr-1">
                    <select name="status" id="status" class="form-control">
                        <option value="">Select Status</option>
                                                <option value="2">Picked</option>
                                                <option value="3">In Transit</option>
                                                <option value="5">Hold</option>
                                                <option value="6">Return Pending</option>
                                                <option value="8">Returned To Merchant</option>
                                                <option value="4">Deliverd</option>
                                    </select>
                   
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <input class="form-control" type="text" id="bercode" name="trackid" placeholder="Enter Bercode..."
                        search>
                </li>
            </ul>

            <ul class="navbar-nav ml-5 p-2 ">
                <li class="nav-item d-none d-sm-inline-block mr-1" style="width:200px;">
                    <select name="rider" id="rider" class="form-control select2">
                        <option value="">Select  Deliverymen</option>
                        @foreach($deliverymen as $men)
                        <option value="{{$men->id}}">{{$men->name}}</option>
                        @endforeach
                    </select>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <input class="form-control" type="text" id="dbercode" name="dtrackid" placeholder="Enter Bercode..."
                        search>
                </li>
                
                <li class="nav-item d-none d-sm-inline-block pl-2" style="margin-left:280px;">
                    <a href="" class="btn btn-warning btn" data-toggle="modal" data-target="#exampleModal1"> Quick Update</a>
                </li>
            </ul>
            <!-- nav item end -->

        </nav>
  <div class="hide-desktop">
      <div class="row m-1">
          <div class="col-6">
              <select name="status" id="status" class="form-control">
                <option value="">Select One</option>
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
  <!-- /.navbar -->

  <!-- Main Sidebar Container sidebar-dark-primary-->
  <aside class="main-sidebar elevation-4 bg-flingex navbar-light">
    <!-- Brand Logo -->
    <a href="{{url('/superadmin/dashboard')}}" class="brand-link">
      <span class="brand-text font-weight-light">Flingex</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="user-image">
          <img src="{{asset(auth::user()->image)}}" class="img-circle" alt="User Image">
        </div>
        <div class="user-info">
          <a href="#" class="d-block">{{auth::user()->name}}</a>
          <i class="fas fa-circle"></i>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column modify-sidebar" data-widget="treeview" role="menu" data-accordion="false">
          
          
         
          <!-- nav item end -->
          <!-- nav item end -->
 @if(Auth::user()->role_id == 1 )
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-user-tie"></i>
              <p>
                Panel User
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/superadmin/user/add')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/superadmin/user/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- nav item end -->
           <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user-tie"></i>
                                <p>
                                    Secret Agent
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{url('/superadmin/sagent/add')}}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Add</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/superadmin/sagent/manage')}}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Manage</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/superadmin/sagent/withdrawal')}}" class="nav-link">
                                        <i class="fas fa-cicle-o"></i>
                                        <p>Withdrawal Request</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
          @endif
          
          @if(Auth::user()->role_id <= 3 )
            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-user"></i>
              <p>
               Announcements 
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">  
              <li class="nav-item">
                <a href="/editor/merchant/notifications" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>New Announcement </p>
                </a>
              </li>
            </ul>
          </li>
          
          
            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-gift"></i>
              <p>
               Parcel Manage
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">  
              <li class="nav-item">
                <a href="/editor/parcel/create" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Parcel Create</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="/editor/parcel/all-parcel" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>All Parcel (@php echo App\Models\Parcel::where('archive',1)->count();  @endphp )</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/editor/parcel/bercode-invoice" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Invoice Track</p>
                </a>
              </li>
              
             {{-- @foreach($parceltypes as $parceltype)
              @php
                  $parcelcount = App\Models\Parcel::where('status',$parceltype->id)->where('archive',1)->count();
                @endphp
              <li class="nav-item">
                <a href="{{url('editor/parcel',$parceltype->slug)}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>{{$parceltype->title}} ({{$parcelcount}})</p>
                </a>
              </li>
              @endforeach--}}
             
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Report
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
              <li class="nav-item">
                <a href="{{url('/admin/Hub-report')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Hub Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/asing/report')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Hub Asign Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/deliveryman/asign')}}" class="nav-link">
                 <i class="fas fa-cicle"></i>
                    <p>Deliveryman Asign Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/parcel/parcelreport')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Parcel Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/typeprepaid')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p>Prepaid Merchant </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/postpaid')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Postpaid Merchant </p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/editor/parcel/report')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Merchant Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/editor/report-collection" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Collection Report</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/editor/merchant/payment/dailyinvoice')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Merchant Daily Invoice Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/reportForERP')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p>Report for ERP</p>
                </a>
              </li>
            </ul>
          </li>
             @if(Auth::user()->role_id <= 2  )
             
          
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Hub
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{url('/admin/agent/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Hub</p>
                </a>
              </li>
            <li class="nav-item">
                <a href="{{url('/admin/thirdpartyagent/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Third Party Hub</p>
                </a>
              </li>
              <li class="nav-item">
                             <a href="{{url('/admin/agent/transactions')}}" class="nav-link">
                                                <i class="fas fa-cicle-o"></i>
                                                <p>Transactions</p>
                                            </a>
                                        </li>
            </ul>
          </li>
          
        
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Merchant
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/editor/merchant-request/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Merchant Request</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/merchant/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
                <li class="nav-item">
                <a href="{{url('/editor/merchant/manage_offer')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Registration Offer</p>
                </a>
              </li>
                
               
             
            </ul>
          </li>
          
          
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Archive  
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/editor/parcel/archive-create')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Archive Parcel Create</p>
                </a>
              </li>
             <li class="nav-item bg-danger">
                <a href="{{url('/editor/parcel/archive-parcel')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Archive Parcel (@php echo App\Models\Parcel::where('archive',2)->count();  @endphp )</p>
                </a>
              </li>
             
            </ul>
          </li>
          
          
         <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-users"></i>
              <p>
                Manage Rider
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/deliveryman/add')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/deliveryman/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage Delivery Man</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/deliveryman/pickupman')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage PickUp Man</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- nav item end -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-map-marker"></i>
              <p>
               Nearest Zone
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/nearestzone/add')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/nearestzone/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- nav item end -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fab fa-angellist"></i>
              <p>
               Delivery Charge
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/deliverycharge/add')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/deliverycharge/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- nav item end -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-award"></i>
              <p>
               Cod Charge
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/codcharge/add')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/codcharge/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- nav item end -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-chart-pie"></i>
              <p>
               HR
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/department/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Department</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/employee/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Employee</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/agent/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Agent</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/deliveryman/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Delivery Man</p>
                </a>
              </li>
            </ul>
          </li>
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-bookmark"></i>
              <p>
                  Website
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/editor/logo/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Logo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/banner/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Banner</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/about/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>About</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/service/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Service</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/editor/branch/manage')}}" class="nav-link">
                 <i class="fas fa-sitemap"></i>
                    <p>Branches</p>
                </a>
              </li>
               
              <li class="nav-item">
                <a href="{{url('/editor/price/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Pricing</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/feature/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Feature</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/clientfeedback/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Client Feedback</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/career/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Career</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/gallery/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Gallery</p>v
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/notice/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Notice</p>
                </a>
              </li>
            </ul>
          </li>
             <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-truck"></i>
              <p>
               Pickup Request
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{url('/editor/new/pickdrop')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Pick & Drop </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/new/pickup')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>New Pickup ({{$newpickup->count()}})</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/pending/pickup')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Pending Pickup ({{$pendingpickup->count()}})</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/accepted/pickup')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Accepted Pickup ({{$acceptedpickup->count()}})</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/editor/cancelled/pickup')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Cancelled Pickup ({{$cancelledpickup->count()}})</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- nav item end -->
 @endif

          @endif
          
       
           @if(Auth::user()->role_id ==4  )
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Report
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            
              <li class="nav-item">
                <a href="{{url('/author/hub/report')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Hub Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/author/asign/report')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Hub Asign Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/author/parcel/report')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Parcel Report</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/author/marchent/report')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Merchant Report</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/editor/merchant/payment/dailyinvoice')}}" class="nav-link">
                  <i class="fas fa-cicle-o"></i>
                    <p> Merchant Daily Invoice Report</p>
                </a>
              </li>
            </ul>
          </li>
           @endif
         @if(Auth::user()->role_id ==3  )
            <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Merchant
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
           
              <li class="nav-item">
                <a href="{{url('/editor/merchant/manage')}}" class="nav-link">
                 <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
             
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fab fa-angellist"></i>
              <p>
               Delivery Charge
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="{{url('/editor/deliverycharge/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
            </ul>
          </li>
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-map-marker"></i>
              <p>
               Nearest Zone
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="{{url('/editor/nearestzone/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Manage</p>
                </a>
              </li>
            </ul>
          </li>
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
            <i class="fas fa-briefcase"></i>
              <p>
                Hub
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{url('/editor/agent/manage')}}" class="nav-link">
                <i class="fas fa-cicle-o"></i>
                    <p>Hub</p>
                </a>
              </li>
            
            </ul>
          </li>
         @endif
          <li class="nav-item">
                <a href="https://erp.flingex.com" target="_blank" class="nav-link">
                <i class="fa fa-database"></i>
                    <p>ERP </p>
                </a>
              </li>
          <!-- nav item end -->
          <!-- nav item end -->
          
          <!-- nav item end -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @yield('content')
  </div>
  <div class="search-product-inner" id="live_data_show"></div> 

  <footer class="main-footer">
    <strong>Copyright &copy;<a href="{{url('/')}}">Flingex</a></strong>
    All rights reserved.
  </footer>
<div class="modal fade" id="exampleModal1" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <thead>
                        <tr>
                            <td>Excel File Column Instruction <a href="{{asset('public/frontEnd/images/status.xlsx')}}"
                                    download> (Template ) </a></td>
                        </tr>
                    </thead>
                    <table class="table table-bordered table-striped mt-1">
                        <tbody>
                            <tr>
                                <td>Track Id</td>
                                <td>Status</td>
                               

                            </tr>
                        </tbody>
                    </table>
                    <form action="{{url('editor/parcel/statusimport')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload Excel</label>
                            <input class="form-control" type="file" name="status" accept=".xlsx, .xls">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- jQuery -->
<script src="{{asset('backEnd/')}}/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<!--<script src="{{asset('public/backEnd/')}}/plugins/jquery-ui/jquery-ui.min.js"></script>-->
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.4/src/table2csv.min.js"></script>
<script src="{{asset('backEnd/')}}/plugins/bootstrap/js/popper.min.js" ></script>
<script src="{{asset('backEnd/')}}/plugins/bootstrap/js/bootstrap.min.js" ></script>
<!--<script src="{{asset('public/backEnd/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
<script src="{{asset('backEnd/')}}/dist/js/adminlte.js"></script>
<!-- Summernote -->
<!--<script src="//cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script>-->


 <script>
 
  // Replace the <textarea id="editor1"> with a CKEditor 4
  // instance, using default configuration.
  CKEDITOR.replace( 'editor1' );
</script>
<!-- overlayScrollbars -->
<script src="{{asset('backEnd/')}}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- FastClick -->
<script src="{{asset('backEnd/')}}/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{asset('backEnd/')}}/plugins/owlcarousel/owl.carousel.min.js"></script>
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
<!-- flatpicker -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
$("#export").click(function(){

  $("table").tableToCSV();

});
</script>
<script>

    $(function () {
          $('.select2').select2();
     flatpickr("#flatpicker", {
      minDate:"today",
     });
    })
</script>
<script>
  flatpickr(".flatDate",{});
</script>
<script src="{{asset('backEnd/')}}/plugins/select2/js/select2.full.min.js"></script>
<!-- Select2 -->
<!--<script src="{{asset('public/backEnd/')}}/plugins/chart.js/Chart.min.js"></script>-->
<script src="{{asset('backEnd/')}}/plugins/sparklines/sparkline.js"></script>
<script src="{{asset('backEnd/')}}/dist/js/toastr.min.js"></script>
{!! Toastr::render() !!}
<script src="{{asset('backEnd/')}}/dist/js/demo.js"></script>
<!-- ChartJS -->
<script type="text/javascript">
$('#sampleTable').DataTable({order:[]});

</script>
<script>
  $(function () {
   
    //Initialize Select2 Elements
  
    $('#example1').DataTable({
         "paging": true
      
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
       rowReorder: {
            selector: 'td:nth-child(2)'
        },
        responsive: true,
      
    });

  })
</script>
<script type="text/javascript">
    $("#search_data").on('keyup', function(){
       var keyword = $(this).val();
       $.ajax({
        type: "GET",
        url: "{{url('/')}}/search_data/" +keyword,
        data: { keyword: keyword },
        success: function (data) {
          console.log(data);
          $("#live_data_show").html('');
          $("#live_data_show").html(data);
        }
       });
    });
</script>
@yield('page-script')
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
                    var dliveid = $("#delivermanId").val();
                    console.log(ber);
                    
                    if (ber) {
                        $.ajax({
                            cache: false,
                            type: "POST",
                            url: "{{url('/editor/parcel/track/')}}",
                            dataType: "json",
                            data: {
                                trackid: ber,
                                status: st,
                                delivermanId: dliveid
                            },
                            success: function(data) {
                                console.log(data);
                                if (data.success == 1) {
                                    $("#bercode").val(null);
                                    toastr.success('Parcel status update successfully');

                                } else {
                                    $("#bercode").val(null);
                                    toastr.error('This Percel status is Already updated Panel ! !');

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
                            url: "{{url('/editor/parcel/rider/')}}",
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
                                        '<h3>This Bercod is not Your Panel !!</h3>');

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
  function percelDelivery(that) {
    if (that.value == "4") {
            document.getElementsByClassName("customerpaid").style.display = "block";
        } else {
            document.getElementsByClassName("customerpaid").style.display = "none";
        }
    }
</script>
<script>
    function myPrintFunction() {
        window.print();
    }
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
    <script>
        $(document).ready(function() {
        var data = [];
       
         
        $('#examplem').DataTable( {
            data:           data,
            deferRender:    true,
            scrollY:        200,
            scrollCollapse: true,
            scroller:       true
        } );
    } );
    </script>
    <script>
       $(document).ready(function() {
          $('#example').DataTable( {
              dom: 'Bfrtip',
              stateSave: true,
            "pageLength": 50,
          
       
              buttons: [
                  {
                      extend: 'copy',
                      text: 'Copy',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'csv',
                      text: 'Csv',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
                      }
                  },
                  
                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                           columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ,12 ]
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
        "pageLength": 50,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
<!-- page script -->
<script>
//   $(function () {
//     /* ChartJS
//      * -------
//      * Here we will create a few charts using ChartJS
//      */

//     //--------------
//     //- AREA CHART -
//     //--------------

//     // Get context with jQuery - using jQuery's .get() method.
//     var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

//     var areaChartData = {
//       labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
//       datasets: [
//         {
//           label               : 'Digital Goods',
//           backgroundColor     : 'rgba(60,141,188,0.9)',
//           borderColor         : 'rgba(60,141,188,0.8)',
//           pointRadius          : false,
//           pointColor          : '#3b8bba',
//           pointStrokeColor    : 'rgba(60,141,188,1)',
//           pointHighlightFill  : '#fff',
//           pointHighlightStroke: 'rgba(60,141,188,1)',
//           data                : [28, 48, 40, 19, 86, 27, 90]
//         },
//         {
//           label               : 'Electronics',
//           backgroundColor     : 'rgba(210, 214, 222, 1)',
//           borderColor         : 'rgba(210, 214, 222, 1)',
//           pointRadius         : false,
//           pointColor          : 'rgba(210, 214, 222, 1)',
//           pointStrokeColor    : '#c1c7d1',
//           pointHighlightFill  : '#fff',
//           pointHighlightStroke: 'rgba(220,220,220,1)',
//           data                : [65, 59, 80, 81, 56, 55, 40]
//         },
//       ]
//     }

//     var areaChartOptions = {
//       maintainAspectRatio : false,
//       responsive : true,
//       legend: {
//         display: false
//       },
//       scales: {
//         xAxes: [{
//           gridLines : {
//             display : false,
//           }
//         }],
//         yAxes: [{
//           gridLines : {
//             display : false,
//           }
//         }]
//       }
//     }

//     // This will get the first returned node in the jQuery collection.
//     var areaChart       = new Chart(areaChartCanvas, { 
//       type: 'line',
//       data: areaChartData, 
//       options: areaChartOptions
//     })

//     //-------------
//     //- LINE CHART -
//     //--------------
//     var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
//     var lineChartOptions = jQuery.extend(true, {}, areaChartOptions)
//     var lineChartData = jQuery.extend(true, {}, areaChartData)
//     lineChartData.datasets[0].fill = false;
//     lineChartData.datasets[1].fill = false;
//     lineChartOptions.datasetFill = false

//     var lineChart = new Chart(lineChartCanvas, { 
//       type: 'line',
//       data: lineChartData, 
//       options: lineChartOptions
//     })

//     //-------------
//     //- DONUT CHART -
//     //-------------
//     // Get context with jQuery - using jQuery's .get() method.
//     var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
//     var donutData        = {
//       labels: [
//           'Chrome', 
//           'IE',
//           'FireFox', 
//           'Safari', 
//           'Opera', 
//           'Navigator', 
//       ],
//       datasets: [
//         {
//           data: [700,500,400,600,300,100],
//           backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
//         }
//       ]
//     }
//     var donutOptions     = {
//       maintainAspectRatio : false,
//       responsive : true,
//     }
//     //Create pie or douhnut chart
//     // You can switch between pie and douhnut using the method below.
//     var donutChart = new Chart(donutChartCanvas, {
//       type: 'doughnut',
//       data: donutData,
//       options: donutOptions      
//     })

//     //-------------
//     //- PIE CHART -
//     //-------------
//     // Get context with jQuery - using jQuery's .get() method.
//     var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
//     var pieData        = donutData;
//     var pieOptions     = {
//       maintainAspectRatio : false,
//       responsive : true,
//     }
//     //Create pie or douhnut chart
//     // You can switch between pie and douhnut using the method below.
//     var pieChart = new Chart(pieChartCanvas, {
//       type: 'pie',
//       data: pieData,
//       options: pieOptions      
//     })

//     //-------------
//     //- BAR CHART -
//     //-------------
//     var barChartCanvas = $('#barChart').get(0).getContext('2d')
//     var barChartData = jQuery.extend(true, {}, areaChartData)
//     var temp0 = areaChartData.datasets[0]
//     var temp1 = areaChartData.datasets[1]
//     barChartData.datasets[0] = temp1
//     barChartData.datasets[1] = temp0

//     var barChartOptions = {
//       responsive              : true,
//       maintainAspectRatio     : false,
//       datasetFill             : false
//     }

//     var barChart = new Chart(barChartCanvas, {
//       type: 'bar', 
//       data: barChartData,
//       options: barChartOptions
//     })

//     //---------------------
//     //- STACKED BAR CHART -
//     //---------------------
//     var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
//     var stackedBarChartData = jQuery.extend(true, {}, barChartData)

//     var stackedBarChartOptions = {
//       responsive              : true,
//       maintainAspectRatio     : false,
//       scales: {
//         xAxes: [{
//           stacked: true,
//         }],
//         yAxes: [{
//           stacked: true
//         }]
//       }
//     }

//     var stackedBarChart = new Chart(stackedBarChartCanvas, {
//       type: 'bar', 
//       data: stackedBarChartData,
//       options: stackedBarChartOptions
//     })
//   })
</script>
</body>
</html>
