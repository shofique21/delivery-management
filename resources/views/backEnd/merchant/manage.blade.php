@extends('backEnd.layouts.master')
@section('title','Manage Merchant')
@section('content')
<style>
@media screen {
  #printSection {
      display: none;
  }
}

@media print {
  body * {
    visibility:hidden;
  }
  #printSection, #printSection * {
    visibility:visible !important;
  }
  #printSection {
    position:absolute !important;
    left:0;
    top:0;
  }
}
</style>
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="box-content">
          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card custom-card">
                    <div class="col-sm-12">
                      <div class="manage-button">
                        <div class="body-title">
                          <h5>Manage Merchant</h5>
                                                    @if(Auth::user()->role_id <= 2  )  
                          <p class=""><a class="btn btn-sm btn-success" href="{{url('editor/merchant/unpaid')}}">UnPaid Merchant </a></p>
                          @endif
                          
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                    <table id="exampled" class="display nowrap" style="width:100%">
                      <thead>
                      <tr>
                      <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>Discount</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                          @if(Auth::user()->role_id <=3  )  
                        <th>Action</th>
                        @endif
                      </tr>
                     </thead>
                      <tbody>
                        @foreach($merchants as $key=>$value)
                        <tr>
                          <td>{{$value->id}}</td>
                          <td>{{$value->firstName}} {{$value->lastName}}</td>
                          <td>{{$value->companyName}}  <?php $upaid=App\Parcel::where('merchantId',$value->id)->where('merchantpayStatus',null)->whereIn('parcels.status',[4,8])->count(); ?> @if($upaid)<span class="btn btn-sm btn-danger">unpaid({{$upaid}})</span>@endif</td>
                           <td>{{$value->discount}}</td>
                          <td>{{$value->phoneNumber}}</td>
                          <td>{{$value->emailAddress}}</td>
                          <td>{{$value->status==1? "Active":"Inactive"}}</td>
                        @if(Auth::user()->role_id <= 3  )  
                          <td>
                            <ul class="action_buttons dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action Button
                                <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                 @if(Auth::user()->role_id <= 2  ) 
                                <li>
                                  @if($value->status==1)
                                  <form action="{{url('editor/merchant/inactive')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                    <button type="submit" class="thumbs_up" title="unpublished"><i class="fa fa-thumbs-up"></i> Inactive</button>
                                  </form>
                                  @else
                                    <form action="{{url('editor/merchant/active')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="hidden_id" value="{{$value->id}}">
                                      <button type="submit" class="thumbs_down" title="published"><i class="fa fa-thumbs-down"></i> Active</button>
                                    </form>
                                  @endif
                                </li>
                                
                                 <li>
                                      <a class="thumbs_up" href="{{url('editor/merchant/edit/'.$value->id)}}" title="Edit"><i class="fa fa-edit"></i> Edit</a>
                                  </li>
                                 
                                  
                                
                                  <li>
                                      <a class="edit_icon" href="{{url('editor/merchant/payment/invoice/'.$value->id)}}" title="View"><i class="fa fa-list"></i> Invoice</a>
                                  </li>
                                  
                                  <li>
                                    <a href="{{url('editor/merchant/dis/'.$value->id)}}" class="btn btn-sm btn-dark">Discount</a>                                           

                                </li>
                                
                                @endif
                                <li>
                                      <a class="edit_icon" href="{{url('editor/merchant/view/'.$value->id)}}" title="View"><i class="fa fa-eye"></i> View</a>
                                  </li>
                                  <li>
                                      <a class="edit_icon" data-toggle="modal" data-target="#mechantType{{$value->id}}" title="Merchant Type"><i class="fa fa-list"></i> Merchant Type</a>
                                  </li>
                              </ul>
                          </td>
                          @endif
                              <div class="modal fade" id="examp{{$value->id}}" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                            Discount</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <form action="{{url('editor/merchant/discount')}}"
                                                            method="post">
                                                            @csrf
                                                            <label for="">Discount </label>
                                                            <input type="hidden" name="id" value="{{$value->id}}">
                                                            <input type="text" name="discount" id=""
                                                                class="form-control">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                    
                  </div>
                  
                  <!-- merchant type -->
<div class="modal fade" id="mechantType{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <form action="{{url('editor/merchant/merhanttype')}}" method="post">
                                                                @csrf
                                                                <label for="">Merchant type </label>
                                                                <input type="hidden" name="id" value="{{$value->id}}">
                                                                <select class="form-control">
                                                                    <option value="1">Prepaid</option>
                                                                    <option value="2">Prospaid</option>
                                                                </select>
    
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
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
@endsection