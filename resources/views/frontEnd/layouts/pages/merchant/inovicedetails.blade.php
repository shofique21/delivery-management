@extends('frontEnd.layouts.pages.merchant.merchantmaster')
@section('title','Manage Merchant Payment')
@section('content')
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
   <style>
      @page { size: auto;  margin: 0mm; }
      @media print {
        header,
        footer {
            display: none !important;
        }
        html,body{
            visibility:hidden; margin: 0 !important; padding: 0 !important; left: 0 !important;
        }
        .invoice-box{
            visibility :visible;
            right: -100px;
            top:0;
            height: 100%;
            padding-right:200px;
        }
        
      }
    .invoice-box {
        max-width: 900px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    .table.table-bordered.parcel-invoice td {
      padding: 5px 20px;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    p{
        margin:0;
    }
    @media only screen and (max-width: 1023px){
        .invoice-box table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
    </style>
</head>

<body>
  <div style="padding-top: 50px"></div>
  <button onclick="myFunction()" style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px !important;display: block;margin: 0 auto;margin-bottom: 0px;text-align: center;
background: #F32C01;
border-radius: 5px;"><i class="fa fa-print"></i></button>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                @foreach($whitelogo as $logo)
                                <img src="{{asset($logo->image)}}" style="width:100%; max-width:100px;">
                                @endforeach
                            </td>
                            
                            <td>
                                @php $merchantInfo = App\Models\Merchant::find(Session::get('merchantId')); @endphp
                               <p> Invoice #: {{$invoiceInfo->id}}</p>
                                <p> Date : {{date('F d, Y', strtotime($invoiceInfo->created_at))}}</p>
                                <p> Time:  {{date('h:i:s a', strtotime($invoiceInfo->created_at))}}</p>
                                <p>Merchant Name : {{$merchantInfo->companyName}}</p>
                                <p>Merchant Phone : {{$merchantInfo->phoneNumber}}</p>
                                <p></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="table table-bordered parcel-invoice">
            <thead>
                                    <tr>
                                        <td>Tracking ID</td>
                                        <td>Invoice Number</td>
                                        <td> Name</td>
                                        <td> Phone</td>
                                        <td>Total</td>
                                        <th>Partial</th>
                                        <td>Charge</td>
                                        <td>Sub Total</td>
                                        <td>Payment</td>
                                    </tr>
                                </thead>
           <tbody>
                                    @php
                                    $payment = 0;
                                    $subtotal = 0;
                                    $chage = 0;
                                    $partial = 0;
                                    $total = 0;
                                    @endphp
                                    @foreach($inovicedetails as $key=>$value)
                                    <tr class="item">
                                        <td scope="row" class="text-center">{{$value->trackingCode}}</td>
                                        <td scope="row" class="text-center">{{$value->invoiceNo}}</td>
                                        <td class="text-center">{{$value->recipientName}}</td>
                                        <td class="text-center">{{$value->recipientPhone}}</td>
                                        <td class="text-center"> {{round($value->cod,2)}}</td>
                                        <td class="text-center"> <?php if ($value->partial_pay==null) {
                            $partial=0;
                          } else {
                            $partial=$value->cod-$value->partial_pay;
                          }
                           ?> {{$value->partial_pay}}</td>
                                        <td class="text-center"> {{(int)$value->deliveryCharge+(int)$value->codCharge}}</td>
                                        <td class="text-center"> {{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}}</td>
                                        <td class="text-center">{{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}} /-
                                        </td>
                                    </tr>
                                    @php
                                    $payment += ($value->cod-($value->deliveryCharge+$value->codCharge))-$partial;
                                    $subtotal+=($value->cod-($value->deliveryCharge+$value->codCharge))-$partial;
                                    $chage+=$value->deliveryCharge+$value->codCharge;
                                    $partial+=$value->partial_pay;
                                    $total+=$value->cod;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        
    						      <td class="text-end fw-bold" colspan="4">Invoice Payment Total:</td>
    						      
    						      <td class="text-end fw-bolder">{{ round($total,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($partial,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($chage,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($subtotal,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($payment,2)}}</td>
    						    </tr>
                                </tbody>
        </table>
    </div>
    <script>
        function myFunction() {
            window.print();
        }
    </script>
</body>
</html>
@endsection
