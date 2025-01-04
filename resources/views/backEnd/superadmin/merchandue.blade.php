@extends('backEnd.layouts.master')
@section('title','Report Parcel')
@section('content')
<div class="profile-edit mrt-30">
    <div class="row">
       <h3>Merchant Due Bill</h3>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="tab-inner table-responsive">
            <table id="exampled" class="table">
                 <thead>
                   <tr>
                    
                    <th>Merchant ID</th>
                   <th>Company Name</th>
                   <th>Due Amount</th>
                   
                             
                   </tr>
                 </thead>
                <tbody>
                    @php $total = 0;  @endphp
                    
                    @foreach ($merchant as $row)
                    
                 <tr>
                     <?php $total+= App\Models\Parcel::where('status',4)->where('merchantId',$row->id)->where('merchantpayStatus',null)->sum('merchantDue')?>
               
                     <td>{{$row->id}}</td>
                     <td><a href="{{url('editor/merchant/view/'.$row->id)}}" target="_blank">{{$row->companyName}} </a></td>
                     <td><?php echo App\Models\Parcel::where('status',4)->where('merchantId',$row->id)->where('merchantpayStatus',null)->sum('merchantDue') ?></td>
                     
                      
                 </tr>
                 @endforeach
              
                  
           
                </tbody>
                <tfoot>
                        <tr>
                      <th>&nbsp</th>
                       
                          <th>Total</th>
                          <th>{{$total}}</th>
                          
                  </tr>
                </tfoot>
               </table>
             </div>
        </div>
    </div>
    <!-- row end -->
</div>
@endsection