@extends('frontEnd.layouts.master')
@section('title','Our Branches | FingEx - Pack, Send and Relax')
@section('description', 'Get all of our branch informations in one place!')
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

<style>
.agent-search .form-control {
    border-color: #1d63af;
}
.agent-search .btn-primary {
    background: #1d63af;
    border-color: #1d63af;
}
.agent-card{
    transition: .2s;
}
.agent-card:hover, .agent-card:focus {
    box-shadow: 0 5px 12px 3px #1d63af !important;
}
@media only screen and (min-width: 1024px){
    .agent-card img.img-responsive {
        max-height: 225px;
        display: block;
        margin: 0 auto;
        width: 100%;
        object-fit: cover;
    }
    .shadow-sm.p-2.mb-4.bg-white.rounded.agent-card {
        min-height: 339px;
    }
}
</style>
      <section class="section-padding">
        <div class="container">
            <h3 class="mb-3 text-center">Our Branches <span id="total_records"></span></h3>
            
            <div class="row">
                <div class="col-md-7 mx-auto">
                   
                    <div class="input-group mb-4 agent-search">
                      <!--<div class="input-group-prepend">-->
                      <!--  <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>-->
                      <!--</div>-->
                      <input type="text" class="form-control" name="search" id="search" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon1">
                    
                    </div>
                </div>
            </div>
            <div class="row" id="branch">
               
                </div>
              
               
                </div>
            </div>
            <!--<table-->
            <!--  id="table"-->
            <!--  data-toggle="table"-->
            <!--  data-ajax="ajaxRequest"-->
            <!--  data-search="true"-->
            <!--  data-side-pagination="server"-->
            <!--  data-pagination="true"-->
            <!--  class="table-sm">-->
            <!--  <thead>-->
            <!--    <tr>-->
            <!--      <th data-field="id">District</th>-->
            <!--      <th data-field="name">Name</th>-->
            <!--      <th data-field="name">Contact</th>-->
            <!--      <th data-field="price">Address</th>-->
            <!--    </tr>-->
            <!--  </thead>-->
            <!--</table>-->
        </div>
    </section>
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
   

<script>
$(document).ready(function(){

 fetch_customer_data();

 function fetch_customer_data(query = '')
 {
  $.ajax({
   url:"{{ route('searchbranches') }}",
   method:'GET',
   data:{query:query},
   dataType:'json',
   success:function(data)
   {
    $('#branch').html(data.table_data);
    //$('#total_records').text(data.total_data);
   }
  })
 }

 $(document).on('keyup', '#search', function(){
  var query = $(this).val();
  fetch_customer_data(query);
 });
});
</script>


@endsection