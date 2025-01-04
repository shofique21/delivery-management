@extends('backEnd.layouts.master')
@section('title','Order Manage')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
    
    
    
    .print-hide{color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px !important;display: block;margin: 0 auto;margin-bottom: 0px;text-align: center;
                background: #F32C01;
                    border-radius: 5px;}
    @media print {
        
  /*body { */
  /*  color : #000000; */
  /*  background : #ffffff; */
  /*  font-family : "Times New Roman", Times, serif;*/
  /*  font-size : 12pt; 
    width: 100%; /*width of index card
    height:2.4409449in /*height of index card*/
  /*}*/
}
          @page { size: auto;  margin: 0mm; }
      @media print {
        header,
        footer {
           {page-break-after: always;}
        }
        .main-footer, .print-hide, .navbar{display: none;}
      }
   
    .container-fluid {
        max-width: 900px
    }

    .top-space {
        margin-top: 4.5rem;
    }

    body {
        font-size: 14px;
    }

    img.fullwidth {
        width: 100%;
    }

    .table-sm>:not(caption)>*>* {
        padding: .12rem .25rem;
    }
    @media (max-width: 1500px){
        .invoice-table td, .invoice-table th{
            max-width: 100%;
        }
    }

    /*@media print {*/
    /*    .wrap {*/
    /*        height: 100vh;*/
    /*        overflow-y: scroll;*/
    /*    }*/

    /*    footer {*/
    /*        position: fixed;*/
    /*        left: 0;*/
    /*        bottom: 0;*/
    /*        width: 100%;*/
    /*    }*/
    /*}*/
    @media only screen and (max-width: 100%) {
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
     <div class="wrap"  id="divId" >
        <header>
            <button class="print-hide" onclick="myFunction()"><i class="fa fa-print"></i></button>
            <div class="invoice-box">
                <div class="container-fluid">
                    <div class="top">
                        <img src="https://flingex.com/invoice/invoice-head.png" alt="header" class="fullwidth">
                    </div>
                    <div class="row">
                        <h1 class="text-center mb-0 fw-bolder">INVOICE</h1>
                    </div>
                    <div class="border-top border-bottom mb-2 pt-1 pb-1" style="background: #f8f9fa">
                        <div class="row">
                            <div class="col-7">
                                <strong>Merchant ID</strong>: {{$merchantInfo->id}}<br>
                                <strong>Merchant Name</strong>: {{$merchantInfo->companyName}}<br>
                                <strong>Mobile Number</strong>: {{$merchantInfo->phoneNumber}}
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <strong>Invoice No</strong>: {{$invoiceInfo->id}}<br>
                                        <strong>Date</strong>: {{date('F d, Y', strtotime($invoiceInfo->created_at))}},
                                        <strong>Time</strong>:{{date('h:i:s a', strtotime($invoiceInfo->created_at))}}<br>
                                        <strong style="margin-right: 150px;">Prepared By :</strong> {{$invoiceInfo->user}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        </header>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm invoice-table">
                                <thead>
                                    <tr>
                                        <td>Tracking ID</td>
                                        <td>Invoice Number</td>
                                        <td> Name</td>
                                        <td> Phone</td>
                                        <td>Total</td>
                                        <th>Partial</th>
                                        <td>C.Charge</td>
                                        <td>D.Charge</td>
                                        <td>Sub Total</td>
                                        <td>Payment</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $payment = 0;
                                    $subtotal = 0;
                                    $dchage = 0;
                                    $ccharge=0;
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
                                        <td class="text-center"> {{(int)$value->codCharge}}</td>
                                        <td class="text-center"> {{(int)$value->deliveryCharge}}</td>
                                        <td class="text-center"> {{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}}</td>
                                        <td class="text-center">{{((int)$value->cod-(int)($value->deliveryCharge+$value->codCharge))-$partial}} /-
                                        </td>
                                    </tr>
                                    @php
                                    $payment += ($value->cod-($value->deliveryCharge+$value->codCharge))-$partial;
                                    $subtotal+=($value->cod-($value->deliveryCharge+$value->codCharge))-$partial;
                                    $dchage+=$value->deliveryCharge;
                                    $ccharge+=$value->codCharge;
                                    $partial+=$value->partial_pay;
                                    $total+=$value->cod;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        
    						      <td class="text-end fw-bold" colspan="4">Invoice Payment Total:</td>
    						      
    						      <td class="text-end fw-bolder">{{ round($total,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($partial,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($ccharge,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($dchage,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($subtotal,2)}}</td>
    						      <td class="text-end fw-bolder">{{ round($payment,2)}}</td>
    						    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="col-12">
                        <strong>In words</strong>: Six thousand Taka
                    </div> -->
                </div>
            </div>
        </section>
        <footer>
            <div class="container-fluid">
                <div class="border-top mt-1">
                    <div class="row">
                        <div class="col-4 text-start">
                            <span style="text-decoration: overline; display: inline-block;" class="top-space"> &nbsp;
                                &nbsp; &nbsp; Merchant Signature &nbsp; &nbsp; &nbsp; </span>
                        </div>
                        <div class="col-4 text-center">
                            <span style="text-decoration: overline; display: inline-block;" class="top-space"> &nbsp;
                                &nbsp; &nbsp; Checked By &nbsp; &nbsp; &nbsp; </span>
                        </div>
                        <div class="col-4 text-end">
                            <span style="text-decoration: overline; display: inline-block;" class="top-space"> &nbsp;
                                &nbsp; &nbsp; Authorised By &nbsp; &nbsp; &nbsp; </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <img src="https://flingex.com/invoice/invoice-footer.png" alt="Footer" class="fullwidth">
                </div>
            </div>
        </footer>
    </div>
</body>
<script>
      function myFunction() {
            window.print();
        }
    </script>
</html>
@endsection