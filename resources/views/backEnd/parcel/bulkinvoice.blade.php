
<!-- Main content -->
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.8.0/JsBarcode.all.js"></script>
    <style>
    @page {
        size: auto;
        margin: 0mm;
    }

    @media print {

        header,
        footer {
            display: none !important;
        }

        .form-group {
            margin-bottom: 0;
        }

        .action_buttons ul {
            padding: 0px 15px;
        }

        .invoice-date {
            padding: 5px 0;
            margin: 7px 0;
        }

        .codingo ul {
            margin: 15px 0;
        }

        .codingo ul li {
            padding: 0px 0;
        }

        .shipping-info {
            margin-top: 7px;
        }

        .invoice-logo {
            margin: 2px auto;
        }

        button,
        .bar-code {
            display: none;
        }
    }


    .modal-body.printSection {
        max-width: 100%;
        width: 90%;
        border: 1px solid #222;
        margin: 5px auto 5px;
    }

    .invoice-box {
        max-width: 58mm;
        margin: 0 auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 90%;
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

    .invoice-box table tr.item td {
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

    p {
        margin: 0;
    }

    .invoice-logo img {
        width: 180px;
    }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
    /*only for parcel invoice*/
    .invoice-area-wrap {
        font-family: 'Poppins';
    }

    .invoice-wrap>div {
        display: flex;
        justify-content: space-between;
        border: 1px solid #ddd;
        margin: 2px 0 2px;
        padding: 5px;
        align-items: center;
    }

    .hub,
    .merchant {
        border-left: 1px solid #ddd;
        padding-left: 5px;
    }

    .invoice-wrap {
        border: 1px solid #ddd;
        padding: 1px;
        margin: 5px 0;
        max-width: 100mm;
        font-size:25px;
        /* float: left; */
        /*margin-right: 20px;*/
        margin-bottom: 20px;
        /* display: block; */
        margin-left: auto;
        margin-right: auto !important;
    }

    .invoice-wrap:last-child {
        margin-right: 0;
    }

    .invoice-wrap p {
        font-size: 12.5px;
        line-height: 1.6;
    }

    .invoice-wrap img {
        max-width: 100%;
    }

    .body-s,
    .fw-bold {
        font-weight: 700;
    }

    .qrcode {
        margin-right: 5px;
    }

    .top .merchant {
        width: 85%;
    }

    .invoice-area .container {
        overflow: hidden;
    }

    .logo img {
        height: 25px;
        width: auto;
    }

    p.l-justify {
        letter-spacing: 2px;
        font-weight: 700;
    }

    .small p {
        font-size: 10.5px;
    }

    .hub {
        width: 50%;
    }

    .bottom img {
        height: 120px;
        width: auto;
    }

    /*only for parcel invoice*/
    </style>
    <style>
    @media print {

        header,
        footer,
        #cta,
        .quicktech-all-page-header-bg {
            display: none;
        }
    }

    .pagination-detail .dropdown-item:hover,
    .pagination-detail .dropdown-item:focus {
        color: #fff !important;
    }
    </style>

</head>

<body >
    <!-- Modal content-->
    
        
        
            
            
            
          
             <!-- only for parcel invoice -->
            <button onclick="printDiv()"
                style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px;background: green; position: relative; left: 46.5%; top: 2px">Bulk Invoice Print</button>
                   
<div class="invoice-area-wrap "  id="DivIdToPrint">                   
@foreach($parcel as $pid)
    <?php 
   $show_data = DB::table('parcels')
        ->join('merchants', 'merchants.id','=','parcels.merchantId')
        ->join('nearestzones', 'parcels.reciveZone','=','nearestzones.id')
        ->where('parcels.id',$pid)
        ->select('parcels.*','nearestzones.zonename','merchants.companyName','merchants.phoneNumber','merchants.emailAddress')
        ->first();
?>
            <section class=" " id="">
                <style>
                /*only for parcel invoice*/
                @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
                .invoice-wrap {
                    font-family: font-family: 'Poppins', sans-serif;
                }
            
                .updated.invoice-wrap>div {
                    display: flex;
                    justify-content: space-between;
                    border: 1px solid #ddd;
                    margin: 0;
                    padding: 1px 5px;
                    align-items: center;
                }
            
                .hub,
                .merchant {
                    border-left: 1px solid #ddd;
                    padding-left: 5px;
                    width: 62%;
                }
            
                .invoice-wrap {
                    border: 1px solid #ddd;
                    padding: 1px;
                    /*margin: 1px 0;*/
                    max-width: 75mm;
                    height: 75mm;
                    font-size:9px;
                    /* float: left; */
                    /*margin-right: 20px;*/
                    /*margin-bottom: 10px;*/
                    /* display: block; */
                    margin-left: auto;
                    margin-right: auto !important;
                }
            
                .invoice-wrap:last-child {
                    margin-right: 0;
                }
            
                .invoice-wrap.updated p {
                    font-size: 9px;
                    line-height: 1;
                }
            
                .invoice-wrap img {
                    max-width: 100%;
                }
               
            
                .body-s,
                .fw-bold {
                    font-weight: 700;
                    font-family: 'Poppins';
                }
            
                .qrcode {
                    margin-right: 5px;
                }
            
                .top .merchant {
                    width: 55%;
                }
            
                .invoice-area .container {
                    overflow: hidden;
                }
            
                .updated .logo img {
                    height: 47px;
                    width: auto;
                }
            
                p.l-justify {
                    letter-spacing: 2px;
                    font-family: 'Poppins';
                }
            
                .small p {
                    font-size: 9px;
                }
            
                .hub {
                    width: 50%;
                }
            
                .bottom img {
                    height: 120px;
                    width: auto;
                }
                .brcode.full {
                    flex: 1;
                }
                .updated img#barcode1 {
                    margin: 0 auto;
                    display: block;
                    max-height: 120px;
                }
                .logo {
                    margin: 5px auto;
                }

                @media print {
                    .qr-code{display: block;}
                }
                /*only for parcel invoice*/
                </style>
                <div class="invoice-wrap updated">
                    <div class="top">
                        <div class="logo">
                             <img src="https://flingex.com/public/assets/img/logo-2.png" alt=""> 
                        </div>
                        
                    </div>
                    <div class="body">
                        
                        <div class="small">
                            <p class="fw-bold">Merchant: <br>{{$show_data->companyName}}</p>
                            <p class="fw-bold">
                            {{$show_data->phoneNumber}}
                            </p>
                            <p class="fw-bold">
                            INVOICE : {{$show_data->invoiceNo}}
                            </p>
                             <p class="fw-bold">
                            Date : {{$show_data->created_at}}
                            </p>
                        </div>
                        <div class="customer merchant">
                            <p class="fw-bold">Customer: {{$show_data->recipientName}}</p>
                            <p class="fw-bold">
                            {{$show_data->recipientPhone}}</p>
                            <p class="fw-bold">
                            {{$show_data->recipientAddress}}
                            </p>
                            
                            <?php $area=App\Models\Agent::where('id',$show_data->agentId)->first(); ?>
                        <u class="fw-bold">Hub : {{@$area->name}}</u>
                       
                         
                            
                        </div>
                    </div>
                    <div class="body">
                        <p class="fw-bold" style="text-align: center; width: 100%">Tracking ID: {{$show_data->trackingCode}}</p>
                    </div>
                    <div class="body-s">
                        <p class="fw-bold" style="font-size: 12px; text-align: center; width: 100%">COD: {{$show_data->cod}}</p>
                    </div>
                    <div class="bottom5">
                        <div class="brcode full">
                           <img id="barcode1"/>
		                <script>JsBarcode("#barcode1", "<?php echo $show_data->trackingCode; ?>");</script>
                        </div>
                    </div>
                </div>
            </section><br><br><br><br><br><hr>
            
            @endforeach
            </div>
           
            <!-- only for parcel invoice -->
            <script>
           

            function printDiv() 
{

  var divToPrint=document.getElementById('DivIdToPrint');

  var newWin=window.open('','Print-Window');

  newWin.document.open();

  newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

  newWin.document.close();

  setTimeout(function(){newWin.close();},1000);

}
            </script>
       
    
</body>

</html>
<!-- Modal Section  -->

