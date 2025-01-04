@extends('frontEnd.layouts.master')
@section('title','Gallery | FingEx - Pack, Send and Relax')
@section('description', 'Check our gallery for recent updates')
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
 <!-- QuickTech Gal Section start -->
     <div class="quickTech-gal-area section-padding ">
         <div class="container">
             <div class="section-header text-center">
                 <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Gallery</h2>
                 <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
             </div>
             <div class="row">
                @foreach($gallery as $key=>$value)
                 <div class="col-lg-6 col-md-4 col-xs-12 info">
                     <div class="gal-wrapper wow fadeInLeft" data-wow-delay="0.3s">
                         <div class="img">
                             <img class="img-fluid" src="{{asset($value->image)}}" alt="">
                         </div>
                         <div class="site-heading">
                             <p class="mb-3">{{$value->title}}</p>
                         </div>


                     </div>
                 </div>
                 @endforeach
               
             </div>
         </div>
     </div>
     <!-- QuickTech Gal Section End -->


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