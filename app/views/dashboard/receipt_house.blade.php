@extends('dashboard.manage')

@section('dashboard-content')
  <iframe class="preview-pane" type="application/pdf" width="100%" height="500" frameborder="0" style="position:relative;z-index:0"></iframe>
  <canvas width="500" height="300" id="canvas" style="display: none;">Sorry, no canvas available</canvas>
@endsection

@section('script')
    @parent
	
    <?php $items = []; $total = $gross = 0; ?>

       <?php 

		 /* print_r($InvoiceNo);
		Die();  */
		$paid=0;
		$its = $receipt->items()->get();
		
		 /* print_r($invoice->recipient());
		Die();  */
		
		//$InvoiceNo=$receipt->items()->InvoiceHeaderID;
		foreach($its as $key => $item)
		{                     
			$paid += $item->Amount;
			$total += $item->Amount; 
				
			$item_amnt = $item->Amount;				
			array_push($items, [($receipt->ReferenceNumber), (date('d/m/Y',strtotime($receipt->ReceiptDate))),($Description), number_format($item_amnt, 2),number_format($Balance, 2)]);       			
		}
		$gross=$total;		
      ?>

    <?php array_push($items, [ '','', 'Gross Total', number_format($gross, 2),'' ]); ?>
	
	<?php //print_r($items); Die(); ?>
	

    @if(count($items) > 1)
    <script type="text/javascript">
      var cust = <?php echo json_encode($customer->CustomerID); ?>;
	  var business =<?php echo json_encode($invoice->recipient()); ?>;
      var address =<?php echo json_encode($invoice->business->PostalCode); ?>;//
      var phone = <?php 	echo json_encode($invoice->business->Telephone1); ?>;//
      var email  =<?php echo json_encode($invoice->business->Email); ?>;//
      var iss  = <?php echo json_encode(\Carbon\Carbon::createFromTimeStamp(strtotime($item->CreatedDate))->toFormattedDateString()); ?>;
      var total = <?php echo json_encode(number_format($gross, 2)); ?>;
      var ref =<?php echo json_encode($InvoiceNo); ?>;
	  var receiptid =<?php echo json_encode($receipt->ReceiptID); ?>;

      $("#canvas").JsBarcode(receiptid,{displayValue:true, fontSize:20});

      $(document).ready(function(){
        var img = undefined;
        var convertImgToBase64 = function(url, callback, outputFormat){
            var img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function(){
                var canvas = document.createElement('CANVAS');
                var ctx = canvas.getContext('2d');
                canvas.height = this.height;
                canvas.width = this.width;
                ctx.drawImage(this,0,0);
                var dataURL = canvas.toDataURL(outputFormat || 'image/png');
                callback(dataURL);
                canvas = null;
            };
            img.src = url;
        }

        var columns = ["Reference No", "Deposited Date","Description", "Amount","Balance"];
        var rows = <?php echo json_encode($items) ?>;

        var opts = {
          styles: {overflow: 'linebreak', rowHeight: 10, cellPadding: 2},
          columnStyles: {
              Total: {fontStyle: 'bold', columnWidth: 25}
          },
          margin: {top: 130, left: 10, right: 10, bottom: 10 },
          tableWidth: 200,
          theme: 'grid'
        }
        function up(str) { return str.toUpperCase() }

        convertImgToBase64('/images/stamp-paid.png', function(stamp){
          convertImgToBase64('/images/logo.png', function(logo){
            console.log('making pdf');
            var barcode = document.getElementById('canvas').toDataURL("image/png", 1.0);


            var doc = new jsPDF();
            doc.setFontSize(24);
            doc.text(70, 20, up("Payment Receipt"));
            doc.addImage(logo, 'png', 80, 30, 50, 40);

            doc.setFontSize(18);
            doc.setFontType("bold");
            doc.text(50, 75, "County Government of Uasin Gishu");
            doc.setFontSize(12);
            doc.text(80, 80, "P.O Box 40 - 30100, ELDORET");
            doc.text(95, 85, "053-2016000");

            doc.setLineWidth(0.1);
            doc.setDrawColor(213,212,212);
            doc.line(10, 90, 200, 90);
            doc.line(10, 125, 200, 125);

            doc.text(70, 100, ("Receipt To:  " + business));

            doc.setFontSize(10);
            doc.setFontType("normal");
            doc.text(10, 110, ("Postal Address: " + address));
            doc.text(10, 115, ("Phone: " + phone));
            doc.text(10, 120, ("Email : " + email));


            doc.text(150, 110, ("Invoice Number: " + ref));
            doc.text(150, 115, ("Date Receipted : " + iss));
            doc.text(150, 120, ("Amount : KSh. " + total));


            doc.autoTable(columns, rows, opts);
            doc.addImage(stamp, 'png', 70, 170, 80, 50);
            doc.addImage(barcode, 'png', 70, 220, 80, 25);

            $('.preview-pane').attr('src', doc.output('bloburi'));
          });
        });

      });
    </script>
    @endif
@endsection
