@extends('frontEnd.layouts.master')
@section('title','Delivery Man Login')
@section('content')
<style>
.show-mobile{display: none;}
button#form-submit {
    border-color: #2164af;
    min-width: 200px;
}
@media only screen and (max-width: 767px){
    header#header-wrap, #footer, .bread-wrap, .hide-mobile{
        display: none;
    }
    .show-mobile{display: block;}
    section#quickTech-carrier {
        height: 100vh;
        background: #2163af;
        align-items: center;
        padding-top: 150px;
    }
}
</style>
 <!-- Hero Area Start -->
 <div class="bread-wrap">
     <div class="container">
         <nav aria-label="breadcrumb">
             <ol class="breadcrumb">
               
             </ol>
         </nav>
     </div>
 </div>
 <!-- Hero Area End -->
 <!--Quicktech Carrier Section Start -->
     <section id="quickTech-carrier" class="section-padding bg-gray">
         <div class="container">
             <div class="section-header text-center mt-4 hide-mobile">
                 <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Flingex Login</h2>
                 <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
             </div>
             <div class="show-mobile mb-2">
                <img src="https://flingex.com/public/uploads/logo/FlingEx%20Logo%20(Stoke).png" alt="FlingEX" style="max-height: 100px; display: block; margin: 0 auto;">
             </div>
             <div class="row justify-content-md-center">
                 <div class="col-lg-6 col-md-12 col-xs-12">
                     <!--Quicktech Carrier Item Starts -->
                     <div class="quickTech-carrier-item wow fadeInRight" data-wow-delay="0.2s">

                         <div class="contetn">
                             <div class="info-text">
                                 <h3 class="text-center">Deliveryman Login</h3>

                             </div>

                             <div class="contact-block">
                                <form action="{{url('auth/deliveryman/login')}}" method="post">
                                   @csrf
                                     <div class="row">

                                         <div class="col-md-12">
                                             <div class="form-group">
                                                 <input type="text" placeholder="Email Address " id="email" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="email" required data-error="Please enter your Email">
                                                 <div class="help-block with-errors"></div>
                                                  @if ($errors->has('email'))
                                                    <span class="invalid-feedback">
                                                      <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                  @endif
                                             </div>
                                         </div>

                                         <div class="col-md-12">
                                             <div class="form-group">
                                                 <input type="password" placeholder="Password" id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required data-error="Please enter your Password">
                                                 <div class="help-block with-errors"></div>
                                                 @if ($errors->has('password'))
                                                    <span class="invalid-feedback">
                                                      <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                  @endif
                                             </div>
                                         </div>
                                      
                                             <div class="col-md-12 col-xs-12">
                                             <div class="rememberme text-success">
                                                 <input type="checkbox" name="rememberme" id="rememberme"> <label for="rememberme"> Remember Me</label>
                                             </div>
                                         </div>
                                      
                                             <div class="col-6 col-xs-4">
                                             <a href="{{url('/loginOtp')}}" class="text-info">OTP Login</a>
                                         </div>
                                         <div class="col-6 col-xs-8">
                                             <a href="{{url('deliveryman/forget/password')}}" class="text-danger">Forget Password</a>
                                         </div>
                                         <div class="col-md-12">
                                             <div class="submit-button text-center mt-3">
                                                 <button class="btn btn-common btn-success" id="form-submit" type="submit">Login Now</button>
                                                 <div id="msgSubmit" class="h3 text-center hidden"></div>
                                                 <div class="clearfix"></div>
                                             </div>
                                         </div>
                                     </div>
                                 </form>
                             </div>
                         </div>
                     </div>
                     <!--Quicktech Carrier Item Ends -->
                 </div>

             </div>
         </div>
     </section>
     <!--Quicktech Carrier Section End -->
@endsection
