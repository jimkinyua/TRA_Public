@extends('dashboard.manage')

@section('dashboard-content')
  <iframe class="preview-pane" type="application/pdf" width="100%" height="500" frameborder="2" style="position:relative;z-index:0"></iframe>
  <canvas width="500" height="300" id="canvas"  style="display: none;">Sorry, no canvas available</canvas>
@endsection

@section('script')
    {{-- @parent --}}

    <?php $items = []; $total = 0;  ?>
    
    @foreach($invoice->items as $key => $item)
      <?php
	    
        $total += $invoice->total()+$Details[0]->Arrears;
        
        $Desc='Renewal Fees';//$item->service->ServiceName;
       
        if ($Details[0]->Arrears>0){
          $Desc=$Details[0]->Description;
        }		
         
        array_push($items, [ $item->id(), $Desc, number_format($item->Amount, 2) ]);
                      
     
        if ($Details[0]->Arrears>0)
        {
          array_push($items, [ '', 'Arrears', number_format($Details[0]->Arrears, 2) ]);
        }	

         
        ?>
        
    @endforeach
    <?php 
    //  echo '<pre>';
      
    //   Session::get('customer');
    //   exit; 
      ?>
      
    <?php
      if (count($items) > 0)
      // exit('hapa'); 
	  {
        array_push($items, [ '', 'Gross Total', number_format($total, 2) ]);
      }	 
  				
     ?>
    

    @if(count($items) > 1)

      <script type="text/javascript">
        var cust = <?php echo json_encode($customer->CustomerID); ?>;
        var business = <?php echo json_encode(Session::get('customer')) ?>;
        var address = <?php echo json_encode(isset($invoice->business->PostalCode)?$invoice->business->PostalCode:'Not Set'); ?>;
        var phone = <?php echo json_encode(isset($invoice->business->Telephone1)?$invoice->business->Telephone1:'Not Set'); ?>;
        var email  = <?php echo json_encode(isset($invoice->business->Email)?$invoice->business->Email:'Not Set'); ?>;
        var iss  = <?php echo json_encode(\Carbon\Carbon::createFromTimeStamp(strtotime($invoice->InvoiceDate))->toFormattedDateString()); ?>;
        var total = <?php echo json_encode(number_format($total, 2)); ?>;
        var ref = <?php echo json_encode($invoice->id()); ?>;

        $(document).ready(function() {
          // console.log('hapa')
          $("#canvas").JsBarcode(ref, { displayValue:true, fontSize:20 });

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

          var columns = ["Reference", "Item Description", "Total"];
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

          convertImgToBase64('/images/logo1.png', function(logo){
            // console.log('making pdf');
            var barcode = document.getElementById('canvas').toDataURL("image/png", 1.0);

            var doc = new jsPDF();
            doc.setFontSize(24);
            doc.addImage(logo, 'png', 80, 25, 50, 20);

            doc.text(70, 20, up("Payment Invoice"));
           
            doc.setFontSize(18);
            doc.setFontType("bold");
            // doc.text(80, 50, "Tourism Regulatory Authority");
            doc.setFontSize(12);
            doc.text(80, 50, "P.O. Box 40241 - 00100 NAIROBI/KENYA");
            // doc.text(95, 85, "053-2016000");

            doc.setLineWidth(0.1);
            doc.setDrawColor(213,212,212);
            doc.line(10, 90, 200, 90);
            doc.line(10, 125, 200, 125);

            // doc.text(80, 100, ("Invoice To:  " + business));

            doc.setFontSize(10);
            doc.setFontType("normal");
            doc.text(10, 110, ("Postal Address: " + (!!address ? address : '') ));
            doc.text(10, 115, ("Phone: " + (!!phone ? phone : '') ));
            doc.text(10, 120, ("Email : " + (!!email ? email : '') ));


            doc.text(150, 110, ("Reference Number: " + ref));
            doc.text(150, 115, ("Issued : " + iss));
            doc.text(150, 120, ("Amount : KSh. " + total));


            doc.autoTable(columns, rows, opts);
            doc.addImage(barcode, 'png', 70, 220, 80, 25);

            $('.preview-pane').attr('src', doc.output('bloburi'));
          });

        });
      </script>
   @endif
@endsection
