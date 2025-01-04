@extends('frontEnd.layouts.master')
@section('title','Contact - FlingEx')
@section('content')
<!-- page details -->
<!-- banner -->
<div class="quicktech-all-page-header-bg">
 <div class="container">
     <nav aria-label="breadcrumb">
         <ol class="breadcrumb">
            
         </ol>
     </nav>
 </div>
</div>
	 <!-- Contact Section Start -->
     <section id="contact" class="section-padding bg-gray">
         <div class="container">
             <div class="section-header text-center">
                 <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Contact Us</h2>
                 <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
             </div>
             <div class="row contact-form-area wow fadeInUp" data-wow-delay="0.3s">
                 <div class="col-lg-7 col-md-12 col-sm-12">
                     <div class="contact-block">
                      <form action="{{ url('contact') }}" method="POST" enctype="multipart/form-data">
    	@csrf
                             <div class="row">
                                 <div class="col-md-6">
                                     <div class="form-group">
                                         <input type="text" class="form-control" id="name" name="name" placeholder="Name" required data-error="Please enter your name">
                                         <div class="help-block with-errors"></div>
                                     </div>
                                 </div>
                                 <div class="col-md-6">
                                     <div class="form-group">
                                         <input type="text" placeholder="Email" id="email" class="form-control" name="email" required data-error="Please enter your email">
                                         <div class="help-block with-errors"></div>
                                     </div>
                                 </div>
                                 <div class="col-md-12">
                                     <div class="form-group">
                                         <input type="text" placeholder="Subject" id="msg_subject" name="subject" class="form-control" required data-error="Please enter your subject">
                                         <div class="help-block with-errors"></div>
                                     </div>
                                 </div>
                                 <div class="col-md-12">
                                     <div class="form-group">
                                         <textarea class="form-control" id="message" placeholder="Your Message" name="message" rows="7" data-error="Write your message" required></textarea>
                                         <div class="help-block with-errors"></div>
                                     </div>
                                     <div class="submit-button text-left">
                                         <button class="btn btn-common"  type="submit">Send Message</button>
                                         <div id="msgSubmit" class="h3 text-center hidden"></div>
                                         <div class="clearfix"></div>
                                     </div>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </div>
                 <div class="col-lg-5 col-md-12 col-xs-12">
                     <div class="map">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.7354907217086!2d90.36617859308193!3d23.756810076856254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755bf512ac20245%3A0x679cb2c189b82fd8!2zM2hvdXNlLCA0YiBCbG9jayNCLCDgpqLgpr7gppXgpr4gMTIwNQ!5e0!3m2!1sbn!2sbd!4v1641468547002!5m2!1sbn!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>                     </div>
                 </div>
             </div>
         </div>
     </section>
     <!-- Contact Section End -->
@endsection