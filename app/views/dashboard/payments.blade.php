@extends('dashboard.manage')

@section('dashboard-content')
  <form class="ui form" action="{{ route('portal.post.payment') }}" method="post" enctype="multipart/form-data">
    <h3 class="ui dividing header"> Confirm Bank Payment </h3>

    <div id="subform" class="ui basic padded segment">
      <table class="ui selectable attached basic table">
        <thead>
          <tr>
            <th class="four wide">Invoice</th>
            <th class="five wide">Customer</th>
            <th class="three wide">Amount Due</th>
            <th class="four wide">Amount Receipted</th>
          </tr>
        </thead>
        <tbody id="tbody">
          {{--
          @foreach($invoices as $invoice)
            <tr>
              <td> <a href="{{route('application.invoice', [ 'id' => $invoice->id() ])}}">{{$invoice->id()}}</a> </td>
              <td> <a href="{{route('application.invoice', [ 'id' => $invoice->id() ])}}">{{$CustomerName}}</a> </td>
              <td class='due' data-due="{{number_format($invoice->total(),2)}}">KSh. &nbsp; {{number_format($invoice->total(),2)}}</td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="invoice[{{$invoice->id()}}]" placeholder="KSh. " style="padding-left: 25px;">
                </div>
              </td>
            </tr>
          @endforeach
          --}}
        </tbody>
        <tfoot id="tfoot">
          <tr>
            <th>
              <div id="add_invoice" class="ui basic small button" style="border-radius: 0;">
                <i class="plus outline icon"></i>
                Add Invoice
              </div>
            </th>
            <th></th>
            <th>
              <div>
                <strong> Total Due: </br> </strong>
                KSh. <span id="total_due"> Add Invoice </span>
              </div>
            </th>
            <th>
              <div>
                <strong> Total Receipted: </br> </strong>
                KSh. <span id="total_receipted"> Add Invoice </span>
              </div>
            </th>
          </tr>
      </tfoot>
      </table>
    </div>

    <div class="required field">
      <label>Date</label>
      <input type="text" name="date" placeholder="Date of Payment">
    </div>
    <div class="required field">
      <label>Amount </label>
      <input type="text" name="amount" placeholder="Amount Paid">
    </div>
    <div class="required required field">
      <label>Payment Method</label>
      <select class="ui fluid search dropdown" name="method">
        <option value="">Payment Method</option>
        <option value="1">Mpesa</option>
        <option value="3">Bank</option>
        <option value="4">LAIFOMS Receipt</option>
      </select>
    </div>
    <div class="field">
      <label>Issuing Bank</label>
      <select class="ui fluid search dropdown" name="bank">
        <option value="">Bank</option>
        @foreach ($banks as $key=>$bank)
          <option value={{$key}} >{{$bank}}</option>
        @endforeach
      </select>
    </div>
    <div class="field">
      <label>Slip Number</label>
      <input type="text" name="slip_number" placeholder="Slip Number">
    </div>
    <button class="ui button" type="submit">Submit</button>

  </form>
@endsection

@section('script')
  <?php
    $search = json_encode(
    "
    <div id='search_box' class='ui attached segment' style='width: 100%; margin-top: 5px;'>
      <div class='ui grid'>
        <a class='ui right corner label' id='remove'>
          <i class='remove circle icon'></i>
        </a>
        <div class='six wide column'>
          <div id='search_input' class='ui icon mini input'>
            <input id='invoice_number' type='text' placeholder='Invoice Number' style='border-radius: 0;'>
            <i id='search_invoice' class='circular search link icon'></i>
          </div>
        </div>
        <div class='ten wide column'>
          <div id='result'>  </div>
        </div>
      </div>
    </div>
    "
  );
    $error = json_encode(
    "
    <div class='ui left pointing red basic label''>
      Could not find any invoice with that ID
    </div>
    "
  );
    $done = json_encode(
    "
    <div class='ui left pointing green basic label''>
      Done
    </div>
    "
  );
    ?>

    <script type="text/javascript">
      $(document).ready(function() {
        var done = <?php echo $done; ?>;
        var error = <?php echo $error; ?>;
        var searchForm = <?php echo $search; ?>;
        var url = <?php echo json_encode(route('portal.searchinvoice')); ?>;
        var inv_url = <?php echo json_encode(route('application.invoice')); ?>;

        var total_due = 0;
        $('.due').each(function(i, el) {
          total_due += parseInt($(el).data('due').replace(/,/i, ''));
        })
        $( "#total_due" ).html( total_due.toFixed(2) );
        var total_receipted = 0;
        $('.receipted').each(function(i, el) {
          receipted = parseInt($(el).val()) || 0
          total_receipted += receipted;
        })
        $( "#total_receipted" ).html( total_receipted.toFixed(2) );

        console.log('ready!');
        $( "#add_invoice" ).click(function() {

          console.log( "Show form to add invoice" );
          $("#subform").append(searchForm);

          $( "#remove" ).click(function() {
            console.log('closing time');
            $( "#search_box" ).remove();
          })

          $( "#search_invoice" ).click(function() {
            if(! $( "#invoice_number" ).val() == '' ) {

              var path = url.slice(0, -8) + $( "#invoice_number" ).val();
              console.log("search invoice server side", path);
              $("#search_input").addClass("loading");

              $.get( path, function( data ) {
                $("#search_input").removeClass("loading");
                if(JSON.parse(data).status === 'error') {
                  $( "#result" ).html( error );
                } else {
                  $( "#result" ).html( done );

                  var inv_path = inv_url.slice(0, -9) + JSON.parse(data).invoice;
                  //var cust_name=JSON.parse(data).issued_to;

                  var tr = " \
                    <tr> \
                      <td> <a href='{inv_path}'> {inv_id} </a> </td> \
                      <td>  {cust_name}  </td> \
                      <td class='due'>KSh. {inv_bal} </td> \
                      <td > \
                        <div class='ui left corner labeled mini input' style='border-radius: 0;'> \
                          <div class='ui left corner label'> <i class='asterisk icon'  style='color: #d95c5c; font-size: 70%;'></i> </div> \
                          <input type='text' name='{inv_name}' placeholder='KSh. ' style='padding-left: 25px;'> \
                        </div> \
                      </td> \
                    </tr> \
                  ";

                  var supplant = function (str, o) {
                      return str.replace(/{([^{}]*)}/g,
                          function (a, b) {
                              var r = o[b];
                              return typeof r === 'string' || typeof r === 'number' ? r : a;
                          }
                      );
                  };

                  var opts = {
                    inv_path: inv_path,
                    inv_id: JSON.parse(data).invoice,
                    inv_bal: JSON.parse(data).balance.toFixed(2),
                    inv_name: ("invoice[" + JSON.parse(data).invoice + "]"),
                    cust_name: JSON.parse(data).issued_to};

                  $( "#tbody" ).append(supplant(tr, opts));
                }
              });

            }

          });

        });
      });
    </script>
@endsection
