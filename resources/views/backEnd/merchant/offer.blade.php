@extends('backEnd.layouts.master')
@section('title','Manage Merchant')
@section('content')
<!-- Main content -->
<section class="content">
<style>
 .pointer {
     cursor:pointer;
  }
</style>


    <div class="container-fluid">
        <div class="box-content">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card custom-card">
                        <div class="col-sm-12">
                            <div class="manage-button">
                                <div class="body-title">
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                             
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
   Create Offer
  </button>
<div class="row " id="examp">
                                          

                                            <div class="col-md-12" role="document">
                                                <div class="modal-content">
                                                   
                                                    <div class="modal-body">

                                                    <table class="table">
                                                         <tr>
                                                            <th>Id</th>
                                                            <th>Offer Type</th>
                                                            <th>Inside Dhaka</th>
                                                            <th>Outside Dhaka</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                            @foreach($offers as $offer)
                                                        <tr>
                                                            <td>{{$offer->id}}</td>
                                                            <td>{{$offer->name}}</td>
                                                            <td>{{$offer->inside_amount}}</td>
                                                            <td>{{$offer->outside_amount}}</td>
                                                             <!--<td>{{$offer->status}}</td>-->
                                                            
                                  <td>
                                        <label class="switch">
                                            <input type="checkbox" class="status"
                                                   id="{{ $offer->id }}" {{$offer->status == 1?'checked':''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                                            <td><a onclick="return confirm('Are you sure you want to delete this Offer !!')" href="{{url('editor/merchant/offer/delete/'.$offer->id)}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal{{$offer->id}}">
<i class="fa fa-edit"></i>
</button></td>
                                                        </tr>
                                                         <div class="modal" id="myModal{{$offer->id}}">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Create Offer</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">

                                                   

                                                        <form action="{{url('editor/merchant/create_offer')}}"
                                                            method="post" autocomplete="off">
                                                            @csrf
                                                            <input type="hidden" name="hidden_id" value="{{$offer->id}}">
                                                           <label for="">Offer Name </label>
                                                            <input type="text" name="name" placeholder="Offer Name" id=""
                                                               value="{{$offer->name}}"  class="form-control">
                                                            
                                                           <div class="row">
                                                               <div class="col-md-6">
                                                                    <label for="">Discount </label>
                                                            <input type="text" name="inside_amount" placeholder="Inside Dhaka" id=""
                                                                value="{{$offer->inside_amount}}" class="form-control">
                                                               </div>
                                                                <div class="col-md-6">
                                                                    <label for="">Discount </label>
                                                            <input type="text" name="outside_amount" placeholder="Outside Dhaka" id=""
                                                               value="{{$offer->outside_amount}}" class="form-control">
                                                               </div>
                                                           </div>

                                                

                                                 
                                                   
                                                
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
         </form>
      </div>
    </div>
  </div>
                                                        @endforeach
                                                    </table>

                                                    </div>

                                                
                                                </div>
                                              
                                            </div>
                                        </div>
                                        
                                       <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Create Offer</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">

                                                   

                                                        <form action="{{url('editor/merchant/create_offer')}}"
                                                            method="post" autocomplete="off">
                                                            @csrf
                                                         
                                                           <label for="">Offer Name </label>
                                                            <input type="text" name="name" placeholder="Offer Name" id=""
                                                               value=""  class="form-control">
                                                            
                                                           <div class="row">
                                                               <div class="col-md-6">
                                                                    <label for="">Discount </label>
                                                            <input type="text" name="inside_amount" placeholder="Inside Dhaka" id=""
                                                                value="" class="form-control">
                                                               </div>
                                                                <div class="col-md-6">
                                                                    <label for="">Discount </label>
                                                            <input type="text" name="outside_amount" placeholder="Outside Dhaka" id=""
                                                               value="" class="form-control">
                                                               </div>
                                                           </div>

                                                

                                                 
                                                   
                                                
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
         </form>
      </div>
    </div>
  </div>
                          
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal Section  -->


<script>
      
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "changeStatus",
                method: 'GET',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('Status updated successfully');
                }
            });
        });
    </script>

<!-- Modal -->

@endsection