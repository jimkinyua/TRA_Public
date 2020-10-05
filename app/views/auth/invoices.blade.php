@extends('dashboard.manage')

@section('dashboard-content')

  @if(count($invoices) == 0)
    <h3 class="ui center aligned header">You have not been invoiced yet!</h3>
  @else
    <?php $invoice = $invoices[0]; ?>
    <div id="invoice" class="invoice ui basic center aligned segment">
        {{Api::showLogo('inv-logo text-center',50,50)}}
        <p>
            County Government of Uasin Gishu <br/>
            P.O Box 40 - 30100, ELDORET <br/>
            053-2016000
        </p>

        <div class="ui fitted divider"></div>

        <div class="ui basic segment">

            <div class="ui two column centered grid">
                <div class="ui column">
                    <h3 class="ui center aligned header"> Invoice To: &nbsp;&nbsp;&nbsp; {{$invoice->business}}  </h3>
                </div>
            </div>

            <div class="ui two column centered grid">
                <div class="ui column">
                    Postal Address: {{$invoice->business->PostalCode}} - {{$invoice->business->PostalAddress}} , {{$invoice->business->Town}}<br>
                    Phone: {{$invoice->business->Telephone1}} <br>
                    Email : {{$invoice->business->Email}} <br>
                </div>
                <div class="ui right aligned column">
                    <div class="inv-col"><span>Invoice Number: #</span> {{$invoice->id()}}</div>
                    <div class="inv-col"><span>Issued Date :</span> {{$invoice->CreatedDate}}</div>
                </div>
            </div>
        </div>

    </div>

    <table class="ui selectable striped attached table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Description</th>
                <th></th>
                <th class="text-center">Total</th>
            </tr>
        </thead>

        <tbody>
          <?php $total = $paid = $balance = $receipted = 0; ?>

          @foreach($invoices as $key1 => $invoice)
            <?php $total += $invoice->total(); $paid += $invoice->paid(); $balance += $invoice->balance(); $receipted += $invoice->receipted(); ?>
            @foreach($invoice->items as $key2 => $item)
                <tr>
                    <td>{{++$key2}}</td>
                    <td>
                        <a href="{{route('application.invoice', [ 'id' => $invoice->id() ])}}">{{$item->service}}</a>
                    </td>
                    <td></td>
                    <td class="text-center"><strong>{{$item->Amount}}</strong></td>
                </tr>
            @endforeach
          @endforeach

        </tbody>
    </table>

    <div class="ui grid">
      <div class="three column row">
        <div class="left floated column">
          @if ($balance > 0)
              <h4>Payment Method (MPESA)</h4>
              <ol id="zap_div">
                  <li>Go to <strong>M-PESA</strong> Menu and select <strong>Pay Bill</strong> </li>
                  <li>Enter Business Number: <strong>530100</strong></li>
                  <li>Enter Account Number: <strong> {{$invoice->id()}} </strong></li>
                  <li>Enter Amount:<strong>{{$invoice->total()}}</strong></li>
                  <li>Enter your M-PESA PIN</li>
                  <li>Send & wait for Confirmation then Click the button</li>
              </ol>
          @elseif($receipted > 0)
            <div class="text-center">
              Receipt Received. Awaiting verification
            </div>
          @else
              <div class="text-center">
                {{Api::stampPaid(null,200,200)}}
              </div>
          @endif
        </div>
        <div class="right floated column">
          <td class="text-right">
              <p><strong>Invoiced:</strong></p>
              <p><strong>Paid:</strong></p>
              <p><strong>Acknowledged:</strong></p>
              <p><strong>Balance:</strong></p>
          </td>
          <td class="text-center">
              <p><strong>KES {{number_format($total,2)}}</strong></p>
              <p><strong>KES {{number_format($receipted,2)}}</strong></p>
              <p><strong>KES {{number_format($paid,2)}}</strong></p>
              <p><strong>KES {{number_format($balance,2)}}</strong></p>
          </td>
        </div>
      </div>
      
      <div class="row">
        <div class="column">
          <div class="ui basic segment">
            @if ($balance > 0)
                <div class="">
                  <?php $url = 'admin/pdfdocs/invoices/'.$invoice->id().'.pdf'; ?>
                  <a href="{{route('portal.payment', [ 'invoice' => $invoice->id() ])}}"> <button> Confirm Payment </button> </a>
                  <a href="{{asset($url)}}"> <button> Download as PDF </button> </a>
                </div>
            @else
            <div class="">
              <a href="{{route('portal.receipt', [ 'invoice' => $invoice->id() ])}}"> <button> Edit Payment Receipt </button> </a>
            </div>
            @endif
          </div>
        </div>
      </div>

    </div>

    <h3 class="ui center aligned header">Thank you doing business with us</h3>

    @endif
@endsection

@section('script')
    @parent
    <script type="text/javascript">
    </script>
@endsection
