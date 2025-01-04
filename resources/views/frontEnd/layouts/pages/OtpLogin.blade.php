@extends('frontEnd.layouts.master')
@section('title','Login')
@section('content')
<section id="quickTech-carrier" class="section-padding bg-gray">
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!-- <div class="card-header">{{ __('Login') }}</div> -->
                    <div class="card-body">
                        <form method="POST" action="{{ route('loginWithOtp') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Mobile No') }}</label>

                                <div class="col-md-8">
                                    <!--<input id="mobile" type="number" placeholder="Example : 1635398508 | without 0" class="form-control" name="mobile" required autofocus>-->
                                    <div class="phone-f-otp">
                                  <picture class="flag">
                                        <source media="(width: 86px, height: 50px)" srcset="https://flingex.com/icons/bangladesh-flag.png">
                                       <img src="https://flingex.com/icons/bangladesh-flag.png">
                                   </picture>
                                 <span class="ext">+880</span><input name="mobile" id="mobile" type="number" placeholder="Enter phone number" autofocus minlength="10" maxlength="10" required>
                               </div>

                                </div>
                            </div>



                            <div class="form-group row otp">
                                <label for="password" class="col-md-4 col-form-label text-md-right">OTP</label>

                                <div class="col-md-8">

                                    <input id="otp" type="number" class="form-control" name="otp" >
                                </div>
                            </div>


                            <div class="form-group row mb-0 otp">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                </div>
                            </div>
                            <!-- <button class="btn btn-success" type="submit" onclick="sendOtp()">Send OTP</button> -->
                        </form>
                        <div class="form-group row send-otp">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-success" type="submit" onclick="sendOtp()">Send OTP</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    <!--<section id="quickTech-carrier" class="section-padding bg-gray">-->
    <!--    <div class="container pt-5">-->
    <!--        <div class="row justify-content-center">-->
    <!--            <div class="col-md-8">-->
    <!--                <div class="card quickTech-carrier-item p-4">-->

    <!--                    <div class="card-body text-center otp-form">-->
    <!--                        <p class="mb-3">-->
    <!--                            Enter your phone number and hit "Send OTP" to get an OTP.-->
    <!--                        </p>-->
    <!--                        <form method="POST" action="https://flingex.com/loginWithOtp">-->
    <!--                            <input type="hidden" name="_token" value="AJbxRimHFRI5gQyDxx7NDQZqw1CPiZ7q0OhFiW2A">-->
    <!--                            <div class="phone-f-otp">-->
    <!--                                <picture class="flag">-->
    <!--                                    <source media="(width: 86px, height: 50px)" srcset="https://flingex.com/icons/bangladesh-flag.png">-->
    <!--                                    <img src="https://redx.com.bd/images/bangladesh-flag.png">-->
    <!--                                </picture>-->
    <!--                                <span class="ext">+880</span><input name="mobile" type="number" placeholder="Enter phone number" autofocus minlength="10" maxlength="10" required>-->
    <!--                            </div>-->
    <!--                            <div class="otp-wrap mt-3">-->
    <!--                                <p>Please enter the OTP that we’ve sent to you at +880 1688138948</p>-->
    <!--                                <div class="otp-code">-->
    <!--                                    <input class="otp-field" type="text" name="otp" maxlength="1" style="margin-left: 0px;">-->
    <!--                                    <input class="otp-field" type="text" name="otp" maxlength="1">-->
    <!--                                    <input class="otp-field" type="text" name="otp" maxlength="1">-->
    <!--                                    <input class="otp-field" type="text" name="otp" maxlength="1" style="margin-right: 0px;">-->
    <!--                                </div>-->
    <!--                                <div class="otp-code">-->
    <!--                                    <input id="otp" type="number" class="form-control otp-field-alt" name="otp" maxlength="4">-->
    <!--                                </div>-->
    <!--                                <p>Didn’t recieve the OTP yet? <a href="#">Resend OTP</a></p>-->
    <!--                            </div>-->
    <!--                            <div class="form-group row otp">-->
    <!--                                <div class="col-md-6">-->

    <!--                                    <input id="otp" type="number" class="form-control" name="otp" >-->
    <!--                                </div>-->
    <!--                            </div>-->


    <!--                            <div class="otp-login mt-3">-->
    <!--                                <div class="otp-login-btn">-->
    <!--                                    <button type="submit" class="btn btn-primary">-->
    <!--                                        Login-->
    <!--                                    </button>-->

    <!--                                </div>-->
    <!--                            </div>-->
                                <!-- <button class="btn btn-success" type="submit" onclick="sendOtp()">Send OTP</button> -->
                                
    <!--                        </form>-->
    <!--                        <div class="send-otp mt-3">-->
    <!--                            <div class="send-otp-btn">-->
    <!--                                <button class="btn btn-success" type="submit" onclick="sendOtp()">Send OTP</button>-->
    <!--                            </div>-->
    <!--                        </div>-->
                            
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->



@endsection
