<!DOCTYPE html>
<html lang="en">
<head>
    <title>Flingex | @yield('title','DeliveryMan')</title>
    <!-- Meta tag Keywords -->
     <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, minimum-scale=1.0">
    <meta charset="UTF-8" />
    <meta name="format-detection" content="telephone=yes">

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
     <link href="{{asset('public/frontEnd')}}/css/fontawesome-all.min.css" rel="stylesheet">
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

<body>
    @php
        $deliverymanInfo = App\Models\Deliveryman::find(Session::get('deliverymanId'));
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
                            App\Models\Parcel::where('status',$parceltype->id)->where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->count();
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
                                App\Models\Parcel::where('status',1)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
                                $pickedParcelCount = App\Models\Parcel::where('status',2)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
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
                        App\Models\Parcel::where('status',$parceltype->id)->where('deliverymanId',Session::get('deliverymanId'))->where('archive',1)->count();
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
                                App\Models\Parcel::where('status',1)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
                                $pickedParcelCount = App\Models\Parcel::where('status',2)->where('pickupman_id',Session::get('deliverymanId'))->where('archive',1)->count();
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
         <input type="password" name="password" class="form-control"  placeholder="Update Password ?" value="">
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
  
            <div class="main-body">
                <div class="col-sm-12">
                    @yield('content')
                </div>
            </div>
            <!-- Column End-->
        </div>
    </section>

        <!--Next Day Pick Modal end -->
  <script type="text/javascript" src="https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script>

  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
   
  <script src="{{asset('frontEnd/')}}/js/bootstrap4.min.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="{{asset('frontEnd/')}}/js/swiper-menu.js" ></script>
  <script src="{{asset('backEnd/')}}/dist/js/toastr.min.js"></script>
  {!! Toastr::render() !!}
  <!-- Datatable -->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
  <!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
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
 
     <!-- Web-Fonts -->
<script>

 function readLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img-upload').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

     $("#imgInp").change(function() {
        readLogo(this);
    });
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
      datasets: [
        {
          label               : 'Digital Goods',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [28, 48, 40, 19, 86, 27, 90]
        },
        {
          label               : 'Electronics',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [65, 59, 80, 81, 56, 55, 40]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    var areaChart       = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: lineChartData,
      options: lineChartOptions
    })

    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Chrome',
          'IE',
          'FireFox',
          'Safari',
          'Opera',
          'Navigator',
      ],
      datasets: [
        {
          data: [700,500,400,600,300,100],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    var stackedBarChart = new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>
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
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                      }
                  },
                  {
                      extend: 'excel',
                      text: 'Excel',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                      }
                  },
                  {
                      extend: 'csv',
                      text: 'Csv',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                      }
                  },
                  {
                      extend: 'pdfHtml5',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
                      }
                  },
                  
                  {
                      extend: 'print',
                      text: 'Print',
                      exportOptions: {
                          columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
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
@yield('js')
</body>

</html>
