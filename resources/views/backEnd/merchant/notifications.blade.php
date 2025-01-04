@extends('backEnd.layouts.master')
@section('title','Merchant Notifications')
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
                        
                             
                              <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
   Create Notification
  </button>
  
<div class="row " id="examp">
                                          

                                            <div class="col-md-12" role="document">
                                                <div class="modal-content">
                                                   
                                                    <div class="modal-body">

                                                    <table class="table">
                                                         <tr>
                                                            <th>Id</th>
                                                            <th>Title</th>
                                                            <th>Descriptions</th>
                                                            <th>dates</th>
                                                            <th>Action</th>
                                                        </tr>
                                                            @foreach($notifications as $notification)
                                                        <tr>
                                                            <td>{{$notification->id}}</td>
                                                            <td>{{$notification->title}}</td>
                                                            <td>{{$notification->descriptions}}</td>
                                                            <td>{{$notification->created_at}}</td>
                                                  <td><a onclick="return confirm('Are you sure you want to delete this Notification !!')" href="{{url('editor/merchant/notification/delete/'.$notification->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal{{$notification->id}}">
<i class="fa fa-edit"></i>
</button></td>
                                                        </tr>
                                                         <div class="modal" id="myModal{{$notification->id}}">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Create Notification</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">

                                                   

                                                        <form action="{{url('editor/merchant/create_nofification')}}"
                                                            method="post" autocomplete="off">
                                                            @csrf
                                                            <input type="hidden" name="hidden_id" value="{{$notification->id}}">
                                                           <label for="">Title </label>
                                                            <input type="text" name="title" placeholder="Title" id=""
                                                               value="{{$notification->title}}"  class="form-control">
                                                            
                                                           <div class="row">
                                                                    <label for="">Description </label>
                                                            <textarea name="description" placeholder="Description" class="form-control" rows="4" cols="50">{{$notification->descriptions}}</textarea>

                                                              
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
          <h4 class="modal-title">Create Notification</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">

                                                   

                                                        <form action="{{url('editor/merchant/create_nofification')}}"
                                                            method="post" autocomplete="off">
                                                            @csrf
                                                         
                                                           <label for="">Title</label>
                                                            <input type="text" name="title" placeholder="Title" id=""
                                                               value=""  class="form-control">
                                                            
                                                           <div class="row">
                                                                    <label for="">Description </label>
                                                           <textarea name="description" placeholder="Description" class="form-control" rows="4" cols="50"></textarea>

                                                           </div>

                                                

                                                 
                                                   
                                                
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Save</button>
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