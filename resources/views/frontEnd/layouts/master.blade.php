 <!DOCTYPE html>
 <html lang="en">
 <head>
   
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <title>@yield('title', 'FlingEx - Pack, Send And Relax')</title>
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:title" content="@yield('title', 'FlingEx - Pack, Send And Relax')" />
    <meta property="og:image" content="https://flingex.com/public/frontEnd/images/flingex_og.jpeg" />
    <meta property="og:description" content="@yield('description', 'FlingEx is one of the fastest and reliable courier service organization. We are delivering your parcel to your preferred destination with great care.')" />
    
    <meta property="og:site_name" content="FlingEx" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{  Request::url() }}" />
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="facebook-domain-verification" content="j90fsr5j9t0e0f8lkpdcaoz22hjw8l" />
    <link rel="canonical" href="{{  Request::url() }}" />
   
    <!-- Twitter -->
    <meta name="twitter:title" content="@yield('title', 'FlingEx - Pack, Send And Relax')">
    <meta name="twitter:description" content="@yield('description', 'FlingEx is one of the fastest and reliable courier service organization. We are delivering your parcel to your preferred destination with great care.')">
    <meta name="twitter:image" content="https://flingex.com/public/frontEnd/images/twitter.png">
    <!--<meta name="twitter:site" content="@USERNAME">-->
    <!--<meta name="twitter:creator" content="@USERNAME">-->
     <!--@foreach($whitelogo as $wlogo)-->
     <!--<link rel="shortcut icon" type="image/jpg" href="{{asset($wlogo->image)}}"/>-->
     <!--@endforeach-->
     <!--====== Favicon Icon ======-->
     <link rel="apple-touch-icon" sizes="57x57" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://flingex.com/public/frontEnd/images/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="https://flingex.com/public/frontEnd/images/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://flingex.com/public/frontEnd/images/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://flingex.com/public/frontEnd/images/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://flingex.com/public/frontEnd/images/icon/favicon-16x16.png">
    <link rel="manifest" href="https://flingex.com/public/frontEnd/images/icon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="https://flingex.com/public/frontEnd/images/icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/css/bootstrap.min.css">
          <script src="{{asset('frontEnd')}}/assets/js/jquery-min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
     <!-- Icon -->
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/fonts/line-icons.css">
     <link rel="stylesheet" href="{{asset('frontEnd')}}/css/fontawesome-all.min.css">
     <!-- Owl carousel -->
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/css/owl.carousel.min.css">
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/css/owl.theme.css">
    <link rel="stylesheet" href="{{asset('backEnd/')}}/dist/css/toastr.min.css">
     <!-- Animate -->
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/css/animate.css">
     <!-- Main Style -->
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/css/main.css">
     <!-- Responsive Style -->
     <link rel="stylesheet" href="{{asset('frontEnd')}}/assets/css/responsive.css">
       <!-- select2 -->
    <link rel="stylesheet" href="{{asset('backEnd/')}}/plugins/select2/css/select2.min.css">
     <!-- jQuery first, then Popper.js, then Bootstrap JS -->
     <!--FOR AUTO COMPLETE-->
       <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1103993410144054');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1103993410144054&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/61f1793eb9e4e21181bc098d/1fqbk37dq';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
      <!-- Messenger Chat plugin Code -->
    <!--<div id="fb-root"></div>-->
    <!--  <script>-->
    <!--    window.fbAsyncInit = function() {-->
    <!--      FB.init({-->
    <!--        xfbml            : true,-->
    <!--        version          : 'v10.0'-->
    <!--      });-->
    <!--    };-->

    <!--    (function(d, s, id) {-->
    <!--      var js, fjs = d.getElementsByTagName(s)[0];-->
    <!--      if (d.getElementById(id)) return;-->
    <!--      js = d.createElement(s); js.id = id;-->
    <!--      js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';-->
    <!--      fjs.parentNode.insertBefore(js, fjs);-->
    <!--    }(document, 'script', 'facebook-jssdk'));-->
    <!--  </script>-->

      <!-- Your Chat plugin code -->
      <div class="fb-customerchat"
        attribution="page_inbox"
        page_id="106900561507265">
      </div>
     <!-- Global site tag (gtag.js) - Google Analytics -->
    <!--<script async src="https://www.googletagmanager.com/gtag/js?id=G-RZH3HVBGTY"></script>-->
    <!--<script>-->
    <!--  window.dataLayer = window.dataLayer || [];-->
    <!--  function gtag(){dataLayer.push(arguments);}-->
    <!--  gtag('js', new Date());-->
    
    <!--  gtag('config', 'G-RZH3HVBGTY');-->
    <!--</script>-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-196834012-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-196834012-1');
    </script>
         
 

     <style>
         .otp-form input::-webkit-outer-spin-button,
         .otp-form input::-webkit-inner-spin-button {
           -webkit-appearance: none;
           margin: 0;
         }
         /* Firefox */
         .phone-f-otp input[type=number] {
           -moz-appearance: textfield;
         }
         .phone-f-otp {
             border: 1px solid #f78a1e;
             display: inline-block;
             border-radius: 2px;
         }
         .phone-f-otp input[type=number] {
             border: 0;
             border-left: 1px solid #ddd;
             width: 300px;
             border-radius: 0;
             padding: 10px 7px;
         }
         .phone-f-otp picture.flag {
             padding-left: 10px;
             padding-right: 5px;
         }
         .phone-f-otp span.ext {
             padding-right: 5px;
         }
         .phone-f-otp input[type=number]:focus {
             box-shadow: none;
             outline: none;
         }
         input.otp-field {
             width: 45px;
             border: 2px solid #1d63af;
             height: 45px;
             margin: 10px 6px;
             text-align: center;
             border-radius: 2px;
             transition: .2s;
         }
         .otp-field-alt {
             width: 230px;
             margin: 5px auto;
             border: 2px solid #1d63af;
             text-align: center;
             border-radius: 2px;
             transition: .2s;
         }
         @media only screen and (max-width: 767px){
            .phone-f-otp input[type=number] {
                width: 165px;
                font-size: 12.5px;
            }
            .phone-f-otp span.ext {
                font-size: 12.5px;
            }
            .phone-f-otp picture.flag {
                padding-left: 5px;
                padding-right: 0px;
            }
         }
     </style>
 </head>

 <body>
     <!-- Header Area wrapper Starts -->
     <header id="header-wrap">
         <!-- Navbar Start -->
         <nav class="navbar navbar-expand-md bg-inverse fixed-top scrolling-navbar">
             <div class="container">
                 <!-- Brand and toggle get grouped for better mobile display -->
                 <a href="{{url('/')}}" class="navbar-brand">
                  @foreach($whitelogo as $wlogo)
                  <img src="{{asset($wlogo->image)}}" alt="">
                  @endforeach
                </a> 
                 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                     <i class="lni-menu"></i>
                 </button>
                 <div class="collapse navbar-collapse" id="navbarCollapse">
                     <ul class="navbar-nav mr-auto w-100 justify-content-end clearfix">
                         <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('/')}}">
                                 Home
                             </a>
                         </li>
                         <!--<li class="nav-item {{ Request::is('about-us') ? 'active' : '' }}">-->
                         <!--    <a class="nav-link" href="{{url('about-us')}}">-->
                         <!--        About Us-->

                         <!--    </a>-->

                         <!--</li>-->

                         <li class="nav-item dropdown {{ Request::is('our-service/') ? 'active' : '' }}">
                             <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 Services<i class="lni lni-chevron-down"></i>
                             </a>
                             <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @foreach($services as $key=>$value)
                                 <a class="dropdown-item" href="{{url('our-service/'.$value->id)}}">
                             <i class="lni {{$value->icon}}"></i> {{$value->title}}</a>
                                 @endforeach
                             </div>
                         </li>
                         <li class="nav-item {{ Request::is('pricing') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('')}}#pricing">
                                 Pricing
                             </a>
                         </li>
                         <li class="nav-item {{ Request::is('one-time-service') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('')}}#one-time-service">
                                 Pick & Drop
                             </a>
                         </li>
                         
                          <li class="nav-item {{ Request::is('branches') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('branches')}}">
                                 Branches
                             </a>
                         </li>
                         <li class="nav-item {{ Request::is('gallery') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('gallery')}}">
                                 Gallery
                             </a>
                         </li>
                         <li class="nav-item {{ Request::is('notice') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('notice')}}">
                                 Notice
                             </a>
                         </li>
                         <li class="nav-item {{ Request::is('contact-us') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('contact-us')}}">
                                 Contact
                             </a>
                         </li>
                         <li class="nav-item quicktech-register {{ Request::is('merchant/register') ? 'active' : '' }}">
                             <a class="nav-link " href="{{url('merchant/register')}}">
                                 Register
                             </a>
                         </li>
                         <li class="nav-item quicktech-register {{ Request::is('merchant/login') ? 'active' : '' }}">
                             <a class="nav-link" href="{{url('merchant/login')}}">
                                 Login
                             </a>
                         </li>
                     </ul>
                 </div>
             </div>
         </nav>
         <!-- Navbar End -->

     </header>
      <!-- Hero Area Start -->
     <!-- Header Area wrapper End -->
    @yield('content')
     <!-- Footer Section Start -->
    <!--<div class="alert alert-danger alert-dismissible p-2 m-2">-->
    <!--  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
    <!--  <strong>সম্মানিত মার্চেন্ট, </strong><br>-->
    <!--আপনাদের অবগতির জন্য জানানো যাচ্ছে যে, পবিত্র ঈদুল আজহার আগে ঢাকার বাইরে পার্সেল বুকিং ও পিক-আপের শেষ তারিখ ১৬ জুলাই। আনন্দের সাথে আরও জানাচ্ছি যে, ঢাকার ভেতরে ঈদের দিনেও পার্সেল বুকিং ও ডেলিভারি অব্যাহত থাকবে। -->
    <!--</div>-->
     <footer id="footer" class="footer-area section-padding">
         <div class="container">
             <div class="container">
                 <div class="row">
                     <!--
                     <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12">
                         <div class="widget">
                             <h3 class="footer-logo"><img src="{{asset('public/frontEnd')}}/assets/img/logo.png" alt=""></h3>
                             <div class="textwidget">
                                 <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lobortis tincidunt est, et euismod purus suscipit quis.</p>
                             </div>
                             
                         </div>
                     </div>
-->
                     <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                         <h3 class="footer-titel"> Services</h3>
                         <ul class="footer-link">
                             <li><a href="/our-service/1">Home Delivery</a></li>
                             <li><a href="/our-service/2">Pick and Drop</a></li>
                             <li><a href="/our-service/3">Warehousing</a></li>
                             <li><a href="/our-service/4">Cash On Delivery</a></li>
                             <li><a href="/our-service/5">Logistics Services</a></li>
                        
                             <li><a href="/our-service/6">Local Courier Service</a></li>
                             <li><a href="/our-service/7">Online Parcel Delivery</a></li>
                             <li><a href="/our-service/8">Food Delivery</a></li>

                         </ul>
                     </div>
                     <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                         <h3 class="footer-titel">Quick Links</h3>
                         <ul class="footer-link">
                             <li><a href="{{url('/about-us')}}">
                                     About Us
                                 </a></li>
                             <li><a href="{{url('/')}}#pricing">
                                     Pricing
                                 </a></li>
                             <li><a href="{{url('/')}}#one-time-service">Pick & Drop</a></li>
                             <li><a href="{{url('career')}}">Career</a></li>
                             <li><a href="{{url('/gallery')}}">Gallery</a></li>
                             <li><a href="{{url('/contact-us')}}">Contact Us</a></li>
                             <li><a href="{{url('/merchant/register')}}">Register</a></li>
                             <li><a href="{{url('/merchant/login')}}">Login </a></li>
                         </ul>
                     </div>
                     <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                         <h3 class="footer-titel">Contact</h3>
                         <ul class="address">
                             <li>
                                 <a href="#"><i class="lni-map-marker"></i>4/3, Block: B, Lalmatia, Dhaka-1207</a>
                             </li>
                             <li>
                                 <a href="tel:09642900800"><i class="lni-phone-handset"></i> 09642900800</a>
                             </li>
                             <li>
                                 <a href="mailto:info@flingex.com"><i class="lni-envelope"></i>info@flingex.com
                                 </a>
                             </li>
                         </ul>
                           <ul class="footer-link">
                               <div class="row">
                                    <a class="text-white text-center col-sm-6 mb-2" href="https://flingex.com/flingex.apk" role="button">
                                        <p class="lead">Merchant APP</p>
                                        <img class="d-app-icon" src="https://mmart.com.bd/storage/app/public/png/google_app.png" alt="" style="max-width: 250px">
                                    </a>
                                    <a class="text-white text-center col-sm-6 mb-2" target="_blank" href="/public/riderapp/app-release.apk" role="button">
                                        <p class="lead">Rider APP</p>
                                        <img class="d-app-icon" src="https://mmart.com.bd/storage/app/public/png/google_app.png" alt="" style="max-width: 250px">
                                    </a>
                               </div>
                           
                            
                             <div class="social-icon">
                                 <a class="facebook" href="https://www.facebook.com/flingex" target="_blank"><i class="lni-facebook-filled"></i></a>
                                 <a class="twitter" href="#" target="_blank"><i class="lni-twitter-filled"></i></a>
                                 <a class="instagram" href="https://www.instagram.com/fling.ex/" target="_blank"><i class="lni-instagram-filled"></i></a>
                                 <a class="linkedin" href="https://www.linkedin.com/company/flingex" target="_blank"><i class="lni-linkedin-filled"></i></a>
                             </div>
                         </ul>

                     </div>
                     <!--<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">-->

                         
                     <!--</div>-->
                     <!--<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">-->
                     <!--    <ul class="footer-link">-->

                     <!--        <div class="social-icon">-->
                     <!--            <a class="facebook" href="https://www.facebook.com/packenmove"><i class="lni-facebook-filled"></i></a>-->
                     <!--            <a class="twitter" href="#"><i class="lni-twitter-filled"></i></a>-->
                     <!--            <a class="instagram" href="#"><i class="lni-instagram-filled"></i></a>-->
                     <!--            <a class="linkedin" href="#"><i class="lni-linkedin-filled"></i></a>-->
                     <!--        </div>-->
                     <!--    </ul>-->
                     <!--</div>-->
                     <!--<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">-->

                        
                     <!--</div>-->
                 </div>
             </div>
         </div>
         <div id="copyright">
             <div class="container">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="copyright-content">
                             <p>© 2021 Flingex. All rights reserved. Developed by <a href="https://evertechit.com" target="_blank">Evertech IT</a></p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </footer>
     <!-- Footer Section End -->

     <!-- Go to Top Link -->
     <a href="#" class="back-to-top">
         <i class="lni lni-angle-double-up"></i>
     </a>
    <!-- Button trigger modal -->
    <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#openModal">-->
    <!--  Launch demo modal-->
    <!--</button>-->
    

     <!-- Preloader -->

 <!--     <div id="preloader">
         <div class="loader" id="loader-1">
             <img src="{{asset('public/frontEnd')}}/assets/img/preloader.png" alt="">
         </div>
     </div> -->

     <!-- End Preloader -->


    
     <!-- select2 -->
    <script src="{{asset('backEnd/')}}/plugins/select2/js/select2.full.min.js"></script>
     <script>
       $('.select2').select2();
        $('.otp').hide();
        function sendOtp() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // alert($('#mobile').val());
            confirm('0'+$('#mobile').val()+' This Number is Correct?');
            $.ajax( {
                url:'sendOtp',
                type:'post',
                data: {'mobile': $('#mobile').val()},
                success:function(data) {
                    // alert(data);
                    if(data != 0){
                        $('.otp').show();
                        $('.send-otp').hide();
                    }else{
                        alert('Mobile Number not found');
                    }
                },
                error:function () {
                    console.log('error');
                }
            });
        }
    </script>
    <script>
    $('.dotp').hide();

    function dsendOtp() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // alert('kkk');
        alert("Checking  Your Number "+$('#mobile').val());
        $.ajax({
            url: 'dsendotp',
            type: 'POST',
            data: {
                'mobile': $('#mobile').val()
            },
            success: function(data) {
                // alert(data);
                if (data != 0) {
                    $('.dotp').show();
                    $('.dsend-otp').hide();
                } else {
                    alert('Mobile Number is not found !');
                }
            },
            error: function() {
                console.log('error');
            }
        });
    }
    </script>
     <script src="{{asset('frontEnd')}}/assets/js/popper.min.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/bootstrap.min.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/owl.carousel.min.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/wow.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/jquery.nav.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/scrolling-nav.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/jquery.easing.min.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/main.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/form-validator.min.js"></script>
     <script src="{{asset('frontEnd')}}/assets/js/contact-form-script.min.js"></script>
      <script src="{{asset('backEnd/')}}/dist/js/toastr.min.js"></script>
       {!! Toastr::render() !!}

     <script>
         $(".otp-field").keyup(function () {
             if (this.value.length == this.maxLength) {
               $(this).next('.otp-field').focus();
             }
         });
         $(function() {
             var charLimit = 1;
             $(".otp-field").keydown(function(e) {

                 var keys = [8, 9, /*16, 17, 18,*/ 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145];

                 if (e.which == 8 && this.value.length == 0) {
                     $(this).prev('.otp-field').focus();
                 } else if ($.inArray(e.which, keys) >= 0) {
                     return true;
                 } else if (this.value.length >= charLimit) {
                     $(this).next('.otp-field').focus();
                     return false;
                 } else if (e.shiftKey || e.which <= 48 || e.which >= 58) {
                     return false;
                 }
             }).keyup (function () {
                 if (this.value.length >= charLimit) {
                     $(this).next('.otp-field').focus();
                     return false;
                 }
             });
         });
     </script>
     <script>
    jQuery(".search-bar-input").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input").val();
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/search-branches',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(".search-bar-input-mobile").keyup(function () {
        $(".search-card").css("display", "block");
        let name = $(".search-bar-input-mobile").val();
        if (name.length > 0) {
            $.get({
                url: '{{url('/')}}/search-branches',
                dataType: 'json',
                data: {
                    name: name
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('.search-result-box').empty().html(data.result)
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        } else {
            $('.search-result-box').empty();
        }
    });

    jQuery(document).mouseup(function (e) {
        var container = $(".search-card");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });
</script>
 </body>

 </html>
