@extends('land.services')

@section('service')
  <div class="ui center aligned segment">

    <h2 class="ui header">
      INVOICE {{$invoice->id()}}
      <div class="sub header">
        Issued {{$invoice->CreatedDate}}
      </div>
    </h2>

    {{Api::showLogo('inv-logo text-center',50,50)}}

    <div class="ui list">
      <div class="item">Uasin Gishu County</div>
      <div class="item">P.O Box 40 - 30100, ELDORET</div>
      <div class="item">Phone: +61 3 8376 6284</div>
    </div>

    <div class="ui basic segment">
      <h4 class="ui header">Invoice To</h4>
      <h3 class="ui header">{{$invoice->business}}</h3>
      <div class="ui list">
        <div class="item">{{$invoice->business->PostalCode}} - {{$invoice->business->PostalAddress}} {{$invoice->business->Town}} </div>
        <div class="item">Email : {{$invoice->business->Email}}</div>
        <div class="item">Phone: {{$invoice->business->Telephone1}}</div>
      </div>
    </div>

    <div class="ui basic segment">
      <table class="ui definition table">
        <thead>
          <tr>
            <th></th>
            <th>Item Description</th>
            <th>Amount</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoice->items as $count => $item)
            <tr>
              <td>{{++$count}}</td>
              <td>{{$item->application->service}}</td>
              <td>{{$item->Amount}}</td>
            </tr>
           @endforeach
        </tbody>
         <tfoot class="full-width">
          <tr>
            <th></th>
            <th colspan="4">
              <div class="ui right floated small primary labeled icon basic button">
                <i class="in cart icon"></i> KES. {{$invoice->total()}}
              </div>
              <div class="fluid ui button">
                Total Invoice Amount
              </div>
            </th>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="ui section divider"></div>

    <div class="ui basic segment">
      <div class="ui two column grid">
        @if ($invoice->balance() > 0)
          <div class="column">
            <div class="ui horizontal segment">
              <table class="ui definition table">
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Grand Total</td>
                    <td>KES {{number_format($invoice->total(),2)}}</td>
                  </tr>
                  <tr>
                    <td>Amount Paid</td>
                    <td>KES {{number_format($invoice->paid(),2)}}</td>
                  </tr>
                  <tr>
                    <td>TOTAL DUE (Balance)</td>
                    <td>KES {{number_format($invoice->balance(),2)}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="column">
            <div class="ui horizontal segment">
              <h4 class="ui dividing header">Payment Method (MPESA)</h4>
              <ol class="ui list">
                <li>Go to <strong>M-PESA</strong> Menu and select <strong>Pay Bill</strong> </li>
                <li>Enter Business Number: <strong>530100</strong></li>
                <li>Enter Account Number: <strong> {{$invoice->id()}} </strong></li>
                <li>Enter Amount:<strong>{{$invoice->total()}}</strong></li>
                <li>Enter your M-PESA PIN</li>
                <li>Send & wait for Confirmation then Click the button</li>
              </ol>
            </div>
          </div>
        @else
          <div class="ui basic center aligned segment">
            {{Api::stampPaid(null,200,200)}}
          </div>
        @endif
      </div>
    </div>

    <div class="ui section divider"></div>

  </div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
     });
  </script>
@endsection
