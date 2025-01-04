<div class="row">
	<div class="col-sm-8">
		<p style=" color:white">Cach Collection</p>
	</div>
	<div class="col-sm-4">
		<p style=" color:white">{{Session::get('codpay')}} Tk</p>
	</div>
	</div>
	<!-- row end -->
	<div class="row" style=" color:white">
	<div class="col-sm-8" >
		<p style=" color:white">Delivery Charge</p>
	</div>
	<div class="col-sm-4">
		<p style=" color:white">{{Session::get('pdeliverycharge')}} Tk</p>
	</div>
	</div>
	<!-- row end -->
	<div class="row">
	<div class="col-sm-8">
		<p style=" color:white">Code Charge</p>
	</div>
	<div class="col-sm-4">
		<p style=" color:white">{{Session::get('pcodecharge')}} Tk</p>
	</div>
	</div>
	<!-- row end -->
	<div class="row total-bar">
	<div class="col-sm-8">
		<p style=" color:white">Total Payable Amount</p>
	</div>
	<div class="col-sm-4">
		<p style=" color:white">{{Session::get('codpay') - (Session::get('pdeliverycharge')+Session::get('pcodecharge'))}} Tk</p>
	</div>
	</div>
	<!-- row end -->
	<div class="row">
	<div class="col-sm-12">
		<p class="text-center" style=" color:white">Note : <span class="">If you pick up a request after 7pm ,It will be received the next day</span></p>
	</div>
	</div>
	<!-- row end -->