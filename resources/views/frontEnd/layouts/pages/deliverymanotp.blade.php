@extends('frontEnd.layouts.master')
@section('title','OTP LOGIN')
@section('content')
<section id="quickTech-carrier" class="section-padding bg-gray">
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!-- <div class="card-header">{{ __('Login') }}</div> -->

                    <div class="card-body">
                        <form method="POST" action="{{ route('loginOtp') }}">
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
                                 <span class="ext">+88</span><input name="mobile" id="mobile" type="number" placeholder="Enter phone number" autofocus minlength="10" maxlength="10" required>
                               </div>

                                </div>
                            </div>



                            <div class="form-group row dotp">
                                <label for="password" class="col-md-4 col-form-label text-md-right">OTP</label>

                                <div class="col-md-6">

                                    <input id="otp" type="number" class="form-control" name="otp" >
                                </div>
                            </div>


                            <div class="form-group row mb-0 dotp">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                </div>
                            </div>
                            <!-- <button class="btn btn-success" type="submit" onclick="sendOtp()">Send OTP</button> -->
                        </form>
                        <div class="form-group row dsend-otp">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-success" type="submit" onclick="dsendOtp()">Send OTP</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection