@extends('frontEnd.layouts.pages.deliveryman.master')
@section('title','Deliveryman Commission History')
@section('content')
<form action="{{url('deliveryman/commissionhistory')}}" method="get">
@csrf
    <div class="row p-2">
        
        <div class="col-sm-2">
            <input type="date" class="flatDate form-control" placeholder="Date Form" name="startDate">
        </div>
        <!-- col end -->
        <div class="col-sm-2">
            <input type="date" class="flatDate form-control" placeholder="Date To" name="endDate">
        </div>
        <div class="col-sm-2">
            <input type="submit" class=" form-control" value="submit">
        </div>
    </div>
</form>

<style>
    table {
  width: 100%;
}
</style>

<div class="card-body">
    <table id="example" class="text-center table table-bordered table-striped">
        <thead>
            <tr>
                <!--<th>Delevery Man</th>-->
                <th>Total Parcel</th>
                <th>Commission</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($showCommissionHistory as $key=>$value)
            <tr>
                <td>{{$value->parchel_count}}</td>
                <td>{{$value->commission_amount}}</td>
                <td>{{$value->created_at}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@stop