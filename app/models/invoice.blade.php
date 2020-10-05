@extends('dashboard.manage')

@section('dashboard-content')
  <div id="invoice" class="invoice ui basic center aligned segment">
    <img id="logo-img" src="{{asset('images/logo.png')}}" class="ui centered image">
    <p>
        County Government of Uasin Gishu <br/>
        P.O Box 40 - 30100, ELDORET <br/>
        053-2016000
    </p>

    <div class="ui fitted divider"></div>

    <div class="ui basic segment">

        <div class="ui two column centered grid">
            <div class="ui column">
                <h3 class="ui center aligned header"> Invoice To: &nbsp;&nbsp;&nbsp; {{$invoice->recipient()}}  </h3>
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
                <div>Issued On: {{ \Carbon\Carbon::createFromTimeStamp(strtotime($invoice->CreatedDate))->toFormattedDateString() }}</div>
                <div class="inv-col"><span>Total Due : </span> {{number_format($invoice->total(), 2)}}</div>
            </div>
        </div>
    </div>

    <table class="ui selectable striped attached table">
        <thead>
            <tr>
                <th class="one wide">Reference</th>
                <th class="fourteen wide">Item Description</th>
                <th class="one wide right aligned">Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $key => $item)
            <tr>
                <td>{{$invoice->id()}}#{{$key }}</td>
                <td>
                    <p>{{$item->service}}</p>
                </td>
                <td class="right aligned"><strong>{{$item->Amount}}</strong></td>
            </tr>
        @endforeach
      </tbody>
    </table>

    <div class="ui attached segment">
      <div class="ui grid">
        <div class="six column row">
          <div class="left floated ten wide column">

              @if ($invoice->balance() > 0)
                  <h4 class="ui dividing header">Payment terms: payment due  in 30 days</h4>
                  <h5>Payment by MPESA</h5>
                  <ol id="zap_div">
                      <li>Go to <strong>M-PESA</strong> Menu and select <strong>Lipa  na MPESA</strong> </li>
                      <li>Enter <strong>646464</strong> as the  paybill  number and the  Invoice Serial  Number as the  account number </li>
                      <li>Pay the  amount and enter your  MPESA  pin number when prompted</li>
                      <li>Check your  email  for the  permit if the  amount paid by MPESA  is successfull</li>
                  </ol>
                  <h5>Payment by  Bank</h5>
                  <ol id="zap_div">
                      <li>Enter the  Uasin Gishu  cunty revenue account invoice number as the  account number </li>
                      <li>Check your  mail for notofication and printing of the  permit</li>
                  </ol>
                  <div class="ui ignored info message">
                    <p>
                      Contact us on 0720646464 for any assistance
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
                    <p><strong>Paid:</strong></p>
                    <p><strong>Acknowledged:</strong></p>
                    <p><strong>Balance:</strong></p>
                  </td>
                  <td class="text-center">
                    <p><strong>KES {{number_format($invoice->total(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->receipted(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->paid(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->balance(),2)}}</strong></p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="column">
            <div class="ui basic segment">
              <?php $url = 'admin/pdfdocs/invoices/'.$invoice->id().'.pdf'; ?>
              @if ($invoice->balance() > 0)
                <a href="{{route('portal.payment', [ 'invoice' => ','.$invoice->id() ]) }}"> <button> Confirm Payment </button> </a>
              @else
                <a href="{{route('portal.receipt', [ 'invoice' => $invoice->id() ])}}"> <button> Edit Payment Receipt </button> </a>
              @endif
              <!-- <a href="{{asset($url)}}"> <button> Download as PDF </button> </a> -->
            </div>
          </div>
        </div>

      </div>
    </div>

    <h3 class="ui center aligned header">Thank you doing business with us</h3>
@endsection

@section('script')
    @parent
    <script type="text/javascript">
        $( document ).ready(function() {
            $('#department-menu #invoices').trigger('click');
            $('.ui.dropdown').dropdown();
        });
    </script>
@endsection
