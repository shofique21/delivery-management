@extends('frontEnd.layouts.master')
@section('title','Pick and Drop - FlingEx')
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
    
    <section id="one-time-service" class="section-padding bg-gray">
         <div class="container">
             <div class="section-header text-center">
                 <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Pick & Drop Service</h2>
                 <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
             </div>
<div class="row">
	<div class="col-md-6 wow fadeInLeft" data-wow-delay="0.2s">
		<div class="one-time-price-box">
			<div class="title-box">
				<h3><i class="fas fa-people-carry"></i> Pick & Drop</h3>
			</div>
			<div class="price-box">
				<p class="m-0">
					৳ 150
					<span>/per percel</span>
				</p>
			</div>
			<div class="price-desc">
				<ul class="fa-ul">
					<li><span class="fa-li"><i class="fas fa-check-square"></i></span> Instant Pickup</li>
					<li><span class="fa-li"><i class="fas fa-check-square"></i></span> Instant Delivery</li>
					<li><span class="fa-li"><i class="fas fa-check-square"></i></span> Instant Payment</li>
					<li><span class="fa-li"><i class="fas fa-check-square"></i></span> Same City</li>
					<li><span class="fa-li"><i class="fas fa-check-square"></i></span> Online Dashboard</li>
					<li><span class="fa-li"><i class="fas fa-check-square"></i></span> 3-4 Hours Delivery</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-6 wow fadeInRight" data-wow-delay="0.2s">
         <div class="quickTech-carrier-item">
             <div class="contetn">
                 <div class="contact-block">
                     <form action="{{url('auth/merchant/single-servicer')}}" method="POST">
                         @csrf
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <input type="text" class="form-control" id="address" name="address" placeholder="PickUp Address" required data-error="Please enter your PickUp address">
                                     <div class="help-block with-errors"></div>
                                 </div>
                             </div>
                             <div class="col-md-12">
                                 <select class="custom-select form-control" name="area">
                                     <option selected>Select Delivery Area</option>
                                      @foreach($areas as $area)
									    <option value="{{$area->id}}">{{$area->zonename}}</option>
									    @endforeach
                                 </select>
                             </div>
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <input type="text" name="note" placeholder="Note (Optional)"  class="form-control" >
                                     <div class="help-block with-errors"></div>
                                 </div>
                             </div>
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <input type="text" name="estimate" placeholder="Estimated Parcel (Optional)" class="form-control">
                                     <div class="help-block with-errors"></div>
                                 </div>
                             </div>
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <input type="text" name="phone" placeholder="Phone Number" class="form-control">
                                     <div class="help-block with-errors"></div>
                                 </div>
                             </div>
                             <div class="col-md-12">
                                 
                                 <div class="submit-button text-left">
                                     <button class="btn btn-common" id="form-submit" type="submit">Send Request</button>
                                     <div id="msgSubmit" class="h3 text-center hidden"></div>
                                     <div class="clearfix"></div>
                                 </div>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
	</div>
</div>
         </div>
     </section>

@endsection