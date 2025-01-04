
<!-- Main content -->
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jsbarcode/3.8.0/JsBarcode.all.js"></script>
    
</head>

<body >
    <!-- Modal content-->
    
        
        
            
            
            
          
             <!-- only for parcel invoice -->
            <button onclick="printDiv()"
                style="color: #fff;border: 0;padding: 6px 12px;margin-bottom: 8px;background: green; position: relative; left: 46.5%; top: 2px">Print Now !</button>
            
                   


            <section class="invoice-area-wrap " id="DivIdToPrint">
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
                            
                            <?php $area=\DB::table('agents')->where('id',$show_data->agentId)->first(); ?>
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
            </section>
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
