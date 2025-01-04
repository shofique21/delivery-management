<!doctype html>
<html lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" xmlns:addthis="https://www.addthis.com/help/api-spec"  prefix="og: http://ogp.me/ns#" class="no-js">
<head>
	<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome=1">
    <title>Flingex || QR scanner</title>
 <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, minimum-scale=1.0">
 <meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320">
    <meta charset="UTF-8" />
    <!--<meta name="format-detection" content="telephone=yes">-->

    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!-- //Meta tag Keywords 
    <link rel="shortcut icon" type="image/jpg" href="{{asset('public/frontEnd')}}/images/fav.png"/>
    <!-- Custom-Files -->
    <link rel="stylesheet" href="{{asset('public/frontEnd')}}/css/bootstrap4.min.css">
    <!-- Bootstrap-Core-CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- flaticon -->
    <link rel="stylesheet" href="{{asset('public/frontEnd')}}/css/merchant.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{asset('public/frontEnd')}}/css/swiper-menu.css" type="text/css" media="all" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/dist/css/toastr.min.css">
    <!-- datatable -->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">
    <!-- Style-CSS -->
     <link href="{{asset('public/frontEnd')}}/css/fontawesome-all.min.css" rel="stylesheet">
     <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
     <!--<script src="{{asset('public/backEnd/')}}/plugins/datatables/jquery.dataTables.js"></script>-->
  <script src="{{asset('public/backEnd/')}}/plugins/datatables/dataTables.bootstrap4.js"></script>
  <!--<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>-->
  <!--<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>-->
    <!-- Font-Awesome-Icons-CSS -->
    <!-- //Custom-Files -->
   <!--<script src="{{asset('public/frontEnd/')}}/js/jquery_3.4.1_jquery.min.js"></script>-->
   <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
   	
   <style>
       button.btn.btn-success, input.form-control[type="submit"] {
            width: 100%;
            text-transform: uppercase;
        }
        .fw-bold{ font-weight: 700; color: #1d63af;}
                input.form-control {
            margin-bottom: 2px;
        }
        input.form-control[type="submit"] {background-color: #1d63af;color: #fff;}
        .stats-reportList-inner h5.text-center{text-align: left !important}
        @media screen and (max-width: 767px){
            .card .avatar.text-center {
                display: none;
            }
            .stats-reportList-inner h5.text-center{text-align: center !important}
        }
   </style>
</head>
</head>

<body>

	 @php
        $deliverymanInfo = App\Deliveryman::find(Session::get('deliverymanId'));
    @endphp
     <section class="mobile-menu ">
        <div class="swipe-menu default-theme">
            <div class="postyourad">
                <a href="{{url('deliveryman/dashboard')}}">
                    <img src="{{ asset($deliverymanInfo->image) }}" alt="Logo"/>
                </a>
                 <a href="{{url('deliveryman/qrcode-reader')}}">
<i class="fa fa-qrcode" style="font-size:36px; margin-left:100px;"></i>
                </a>
                 <a  href="{{url('deliveryman/dashboard')}}" class="mobile-username">{{$deliverymanInfo->names}}</a>
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
                            <a href="{{url('/deliveryman/dashboard')}}">
                                <div class="list-icon"><i class="fa fa-home"></i></div>
                                Dashboard
                            </a>
                        </li>
                        <li>
                                <a href="{{url('deliveryman/parcels')}}">
                                    <div class="list-icon"><i class="fa fa-car"></i></div>
                                    All Parcel
                                </a>
                            </li>
                            @if(Session::get('jobstatus')!=2)
                             @foreach($parceltypes as $key=>$parceltype)
                            @php
                            $parcelcount =
                            App\Parcel::where('status',$parceltype->id)->where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->count();
                            @endphp
                            @if($key < 3) @continue @endif <li class="nav-item">
                                <a href="{{url('deliveryman/parcels',$parceltype->slug)}}" class="nav-link">
                                    <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                    {{$parceltype->title}} ({{$parcelcount}})
                                </a>
                                </li>
                                @endforeach
                        <!--<li>-->
                        <!--    <a href="{{url('deliveryman/parcels')}}">-->
                        <!--        <div class="list-icon"><i class="fa fa-car"></i></div>-->
                        <!--        Assigned Parcels-->
                        <!--    </a>-->
                        <!--</li>-->
                        <li>
                            <a href="{{url('deliveryman/transaction')}}">
                                <div class="list-icon"><i class="fa fa-cogs"></i></div>
                               Transaction
                            </a>
                        </li>
                        <li>
                            <a href="{{url('deliveryman/report')}}">
                                <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                Report
                            </a>
                        </li>
                         @else
                                @php
                                $pendingParcelCount =
                                App\Parcel::where('status',1)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
                                $pickedParcelCount = App\Parcel::where('status',2)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
                                @endphp
                                <li class="nav-item">
                                    <a href="{{url('/deliveryman/parcels/pending')}}" class="nav-link">
                                        <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                       Pending ({{$pendingParcelCount}})
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/deliveryman/parcels/picked')}}" class="nav-link">
                                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                           Picked ({{$pickedParcelCount}})
                                        </a>
                                        </li>
                            @endif
                            
                        <li>
                            <a href="#">
                                <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                Settings
                            </a>
                        </li>
                        <li>
                            <a href="{{url('deliveryman/logout')}}">
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
    <section class="main-area">
      <div class="dash-sidebar">
            <div class="sidebar-inner">
            <div class="profile-inner">
                <div class="profile-pic">
                    <a href="#"><img src="{{ asset($deliverymanInfo->image) }}" alt=""></a>
                    
                </div>
                <div class="profile-id">
                    <p>{{$deliverymanInfo->name}}: {{$deliverymanInfo->id}}</p>
                </div>
            </div>
            <div class="side-list">
                <ul>
                    <li>
                        <a href="{{url('/deliveryman/dashboard')}}">
                            <div class="list-icon"><i class="fa fa-home"></i></div>
                            Dashboard
                        </a>
                    </li>
                    <li>
                            <a href="{{url('deliveryman/parcels')}}">
                                <div class="list-icon"><i class="fa fa-car"></i></div>
                                All Parcel
                            </a>
                        </li>
                         @if(Session::get('jobstatus')!=2)
                         @foreach($parceltypes as $key=>$parceltype)
                        @php
                        $parcelcount =
                        App\Parcel::where('status',$parceltype->id)->where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->count();
                        @endphp
                        @if($key > 6) @continue @endif <li class="nav-item">
                            <a href="{{url('deliveryman/parcels',$parceltype->slug)}}" class="nav-link">
                                <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                {{$parceltype->title}} ({{$parcelcount}})
                            </a>
                            </li>
                        @endforeach
                    <!--<li>-->
                    <!--    <a href="{{url('deliveryman/parcels')}}">-->
                    <!--        <div class="list-icon"><i class="fa fa-car"></i></div>-->
                    <!--        Assigned Parcels-->
                    <!--    </a>-->
                    <!--</li>-->
                    <li>
                        <a href="{{url('deliveryman/transaction')}}">
                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                           Transaction
                        </a>
                    </li>
                    <li>
                        <a href="{{url('deliveryman/report')}}">
                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                            Report
                        </a>
                    </li>
                     @else
                                @php
                                $pendingParcelCount =
                                App\Parcel::where('status',1)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
                                $pickedParcelCount = App\Parcel::where('status',2)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
                                @endphp
                                <li class="nav-item">
                                    <a href="{{url('/deliveryman/parcels/pending')}}" class="nav-link">
                                        <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                       Pending ({{$pendingParcelCount}})
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/deliveryman/parcels/picked')}}" class="nav-link">
                                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                                           Picked ({{$pickedParcelCount}})
                                        </a>
                                        </li>
                            @endif
                            
                    <li>
                        <a data-toggle="modal" data-target="#myModal" href="#">
                            <div class="list-icon"><i class="fa fa-cogs"></i></div>
                            
  Setting
                        </a>
                    </li>
                    <li>
                        <a href="{{url('deliveryman/logout')}}">
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
            <div class="heading-bar">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="pik-inner">
                            <ul>
                                <li>
                                    <div class="dash-logo">
                                        <!--<a href="{{url('deliveryman/dashboard')}}"><img src="{{ asset($deliverymanInfo->image) }}" alt=""></a>-->
                                    </div>
                                    
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="heading-right">
                            <ul>
                               <li class="profile-area">
                                    <div class="">
                                        <a class="" href="{{url('deliveryman/qrcode-reader')}}"><img src="{{ asset('public/544587.png') }}" height="60" alt="Logo"/>
                                        </a>
                                        </div>

                                </li>
                                <li class="profile-area">
                                    <div class="profile">
                                        <a class="" ><img src="{{ asset($deliverymanInfo->image) }}" alt="" >
                                        </a>
                                        
                                            <ul>
                                                 <li>
                                                 <!-- Trigger the modal with a button -->
                                            
                                               <!--<a href="#">Setting</a>-->
                                               </li>
                                                <li><a href="{{url('deliveryman/logout')}}">Logout</a></li>
                                            </ul>
                                        </div>

                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
             <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title txt-center">My Profile </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
         

        <div class="modal-body">
            <form action="{{url('deliveryman/profileUpdate')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
               <div class="form-group">
                    <label class="col-sm-4 control-label" for="image">{{__('Profile Image')}}</label>
                    <div class="col-sm-8">
                        <input type="file" id="imgInp" name="image" class="form-control">
                    </div>
                    New :
                             <img width="100px" height="100px" id="img-upload" />
                               Old :
 <img width="100px" height="100px" alt="No Image Yet" src="{{ asset($deliverymanInfo->image) }}" />
                </div>
                 <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">Name</label>
           <input type="text" name="name" class="form-control"  placeholder="Update Full Name "  value="{{$deliverymanInfo->name}}">
         </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="password">Password</label>
           <input type="hidden" name="hidden_id" value="{{Session::get('deliverymanId')}}">
         <input type="password" name="password" class="form-control"  placeholder="Update Your Password ?" value="">
         </div>
        </div>
        <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Profile </button>

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
                                              </form>

    </div>
  </div>
  
	
	<div class="container-fluid">
		<div class="row">
			
			
			<div class="col">
				<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
				<h3 class="text-center"><a href="https://learncodeweb.com/phonegap/how-to-use-instascan-an-html5-qr-scanner/"> QR scanner</a></h3>
				<div class="col-sm-12">
				    <div id="show"></div>
					<!--<video id="preview" class="p-1" style="width:100%; height:300px;"></video>-->
					<video autoplay="" muted="" loop="" playsinline="1" id="preview" preload="auto" width="100%" height="350px;">

				</div>
				<script type="text/javascript">
					var scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5, mirror: false });
					scanner.addListener('scan',function(content){
				// 		alert(content);
						window.location.href=content;
					});
					Instascan.Camera.getCameras().then(function (cameras){
						if(cameras.length>0){
							scanner.start(cameras[1]);
							$('[name="options"]').on('change',function(){
								// if($(this).val()==1){
								// 	if(cameras[0]!=""){
								// 		scanner.start(cameras[0]);
								// 	}else{
								// 		alert('No Front camera found!');
								// 	}
								// }else 
								if($(this).val()==2){
									if(cameras[1]!=""){
										scanner.start(cameras[1]);
									}else{
										alert('No Back camera found!');
									}
								}
							});
						}else{
							console.error('No cameras found.');
							alert('No cameras found.');
						}
					}).catch(function(e){
						console.error(e);
						alert(e);
					});
				</script>
				<!--<div class="btn-group btn-group-toggle mb-5" data-toggle="buttons">-->
				<!--  <label class="btn btn-primary ">-->
				<!--	<input type="radio" name="options" value="2" autocomplete="off" >Back  Camera-->
				<!--  </label>-->
				<!--  <label class="btn btn-secondary">-->
				<!--	<input type="radio" name="options" value="1" autocomplete="off" checked> Front Camera-->
				<!--  </label>-->
				<!--</div>-->
			</div>
			
			
			
		
		</div>
	</div>
	
</body>
</html>
