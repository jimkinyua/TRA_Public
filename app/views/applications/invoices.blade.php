@extends('dashboard.manage')

@section('dashboard-content')

  @if(count($invoices) == 0)
    <h3 class="ui center aligned header">You have not been invoiced yet!</h3>
  @else
    <div class="ui basic segment">
      <h3 class="ui dividing header"> Aggregated Invoices </h3>
      <p> Click on the Reference Number to view the details of a particular invoice </p>
    </div>

    <table class="ui selectable striped attached table" style="margin-bottom: 1em;">
        <thead>
            <tr>
                <th>Invoice No</th>				
                <th>Item Description</th>
                <th class="right aligned">Total</th>
				<th>Receipts</th>
            </tr>
        </thead>

        <tbody>
          <?php $total = $paid = $balance = $receipted = 0; $refs = ''; ?>

          @foreach($invoices as $key1 => $invoice)
            @if(is_object($invoice->items()->first()) > 0)
              <?php
                $paid += $invoice->paid();
                $total += $invoice->total();
                $balance += $invoice->balance();
                $receipted += $invoice->receipted();
                $refs = $refs . ',' . $invoice->id();
				$hid=$invoice->items()->first()->InvoiceHeaderID;				
                ?>

                <tr>
                  <td> <a href="{{route('application.invoice', [ 'id' => $invoice->id() ])}}">{{$invoice->id()}}</a> </td>
                  <td>{{$invoice->items()->first()->service}}<br>{{$invoice->items()->first()->Description}}</td>
                  <td class="right aligned"><strong>{{ number_format($invoice->balance(), 2) }}</strong></td>
				  <td> <a href="{{route('application.receipts', [ 'id' => $hid ])}}">Receipts</a> </td>
                </tr>
              @endif
          @endforeach
        </tbody>

        <tfoot>
          <tr>
            <th></th>
            <th class="right aligned">{{count($invoices)}} Invoices</th>
            <th class="right aligned"><strong> {{ number_format($total, 2) }}</strong></th>
			<th></th>
          </tr>
        </tfoot>
    </table>

    <div class="ui attached segment">
      <div class="ui grid">
        <div class="six column row">
          <div class="left floated ten wide column">

              @if ($balance > 0)
                  <h4 class="ui dividing header">Payment terms: payment due  in 30 days</h4>
                  <h5>Payment by MPESA</h5>
                  <ol id="zap_div">
                      <li>Go to <strong>M-PESA</strong> Menu and select <strong>Lipa  na MPESA</strong> </li>
                      <li>Enter <strong>000000</strong> as the  paybill  number and the  Invoice Serial  Number as the  account number </li>
                      <li>Pay the  amount and enter your  MPESA  pin number when prompted</li>
                      <li>Check your  email  for the  permit if the  amount paid by MPESA  is successfull</li>
                  </ol>
                  <h5>Payment by  Bank - Payment Mode: <B>CASH</B></h5>
                  <ol id="zap_div">
                      <li>Enter the TRA invoice number as the account number </li>
                      <li> Acc. Name: TOURISM REGULATORY AUTHORITY </li>
                      <UL>
                        <li>KCB: Acc No: 1178921034 <strong>(Application Fees)</strong></li>
                        <li>CO OP BANK: Acc No: 01141173587300 <strong>(Licence Fees)</strong></li>
                      </UL>
                      
                      <li>You will get an SMS Notification for the payment</li>
                  </ol>
                  <div class="ui ignored info message">
                    <p>
                      Contact us on 0729 498 442 for any assistance
                    </p>
                  </div>
              @else
                  <div class="text-center">
                    {{Api::stampPaid(null,200,200)}}
                  </div>
              @endif

          </div>
          <div class="right floated six wide column">
            <table class="ui very basic collapsing table">
              <tbody>
                <tr>
                  <td class="text-right">
                    <p><strong>Invoiced:</strong></p>
                    <p><strong>Receipted:</strong></p>
                    <p><strong>Confirmed:</strong></p>
                    <p><strong>Balance:</strong></p>
                  </td>
                  <td class="text-center">
                    <p><strong>KES {{number_format($total,2)}}</strong></p>
                    <p><strong>KES {{number_format($receipted,2)}}</strong></p>
                    <p><strong>KES {{number_format($paid,2)}}</strong></p>
                    <p><strong>KES {{number_format($balance,2)}}</strong></p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="column">
            <div class="ui basic segment">
              <!--@if ($balance > 0) -->
                  <div class="">
                    <?php $url = 'admin/pdfdocs/invoices/'.$invoice->id().'.pdf'; ?>
                    <a href="{{route('portal.payment', [ 'invoice' => $refs ])}}"> <button> Confirm Payment </button> </a>
                    <a href="{{asset($url)}}"> <button> Download as PDF </button> </a>
                  </div>
              <!-- @else
              <div class="">
                <a href="{{route('portal.receipt', [ 'invoice' => $invoice->id() ])}}"> <button> Edit Payment Receipt </button> </a>
              </div>
              @endif -->
            </div>
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
