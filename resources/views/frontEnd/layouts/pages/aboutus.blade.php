@extends('frontEnd.layouts.master')
@section('title','About | FingEx - Pack, Send and Relax')
@section('description', 'FlingEx is committed to deliver your parcel to the destination with great care. We are working hard day and night to ensure the best quality service for you!')
@section('content')
<div class="quicktech-all-page-header-bg">
         <div class="container">
             <nav aria-label="breadcrumb">
                 <ol class="breadcrumb">
                    
                 </ol>
             </nav>
         </div>
     </div>
     <!-- Hero Area End -->
      <!-- About Section start -->
     <div class="about-area section-padding">
         <div class="container">
            <div class="section-header text-center">
                 <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">About Us</h2>
                 <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
             </div>
             @foreach($aboutus as $key=>$value)
             <div class="row">
                 <div class="col-lg-6 col-md-12 col-xs-12 info">
                     <div class="about-wrapper wow fadeInLeft" data-wow-delay="0.3s">
                         <div>
                             <div class="site-heading">
                                 <p class="mb-3">{{$value->title}}</p>
                                 <h2 class="section-title">{{$value->subtitle}}</h2>
                             </div>
                             <div class="content">
                                 <p>
                                     {!! $value->text !!}
                                 </p>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-12 col-xs-12 wow fadeInRight" data-wow-delay="0.3s">
                     <img class="img-fluid" src="{{asset('public/frontEnd')}}/assets/img/about/about-flingex.png" alt="About">
                 </div>
             </div>
             @endforeach
         </div>
     </div>
     <!-- About Section End -->
     <!-- Call To Action Section Start -->
     <section id="cta" class="section-padding bg-lightblue">
         <div class="container">
             <div class="row">
                 <div class="col-lg-8 col-md-8 col-xs-12 wow fadeInLeft" data-wow-delay="0.3s">
                     <div class="cta-text">
                         <!--<h4>Get free Register Now</h4>-->
                         <h5 class="mb-0 heading-font">Struggling on sending parcels efficiently? Give a try with us!</h5>
                     </div>
                 </div>
                 <div class="col-lg-4 col-md-4 col-xs-12 text-right wow fadeInRight" data-wow-delay="0.3s">
                     <a href="/merchant/register" class="btn btn-common">Register Now</a>
                 </div>
             </div>
         </div>
     </section>
     <!-- Call To Action Section Start -->

@endsection