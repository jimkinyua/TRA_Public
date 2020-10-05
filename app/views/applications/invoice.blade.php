@extends('dashboard.manage')

@section('dashboard-content')
  <div id="invoice" class="invoice ui basic center aligned segment">
    <img id="logo-img" src="{{asset('images/logo1.png')}}" class="ui centered image">
    <p>
        Tourism Regulatory Authority <br/>
        P.O. Box 40241 - 00100 NAIROBI/KENYA <br/> 
        
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
                Postal Address: {{isset($invoice->business->PostalCode)?$invoice->business->PostalCode:'Not Set'}} - {{isset($invoice->business->PostalAddress)?$invoice->business->PostalAddress:'Not Set'}} , {{isset($invoice->business->Town)?$invoice->business->Town:'Not Set'}}<br>
                Phone: {{isset($invoice->business->Telephone1)?$invoice->business->Telephone1:'Not Set'}} <br>
                Email : {{isset($invoice->business->Email)?$invoice->business->Email:'Not Set'}} <br>
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
                    <p>{{$item->Description}}</p>
                </td>
                <td class="right aligned"><strong>{{number_format($item->Amount,2)}}</strong></td>
            </tr>
			<?php if ($Details[0]->Arrears>0) { ?>
			<tr>
			   <td></td>
			   <td>Arrears:</td>
			   <td class="right aligned"><strong>{{number_format($Details[0]->Arrears,2)}}</strong></td>
			</tr>
			<?php }?>
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
                      <li>Enter <strong>12345</strong> as the  paybill  number and the  Invoice Serial  Number as the  account number </li>
                      <li>Pay the  amount and enter your  MPESA  pin number when prompted</li>
                      <li>Check your  email  for the  permit if the  amount paid by MPESA  is successfull</li>
                  </ol>
                  <h5>Payment by  Bank</h5>
                  <ol id="zap_div">
                      <li>Enter the  Tourism Regulatory Authority account invoice number as the  account number </li>
                      <li>You will get an sms notofication for the payment</li>
                  </ol>
                  <div class="ui ignored info message">
                    <p>
                      Contact us on +254 (20) 2379407 Or +254 (20) 2379408 for any assistance
                    </p>
                  </div>
              @else
                  <div class="text-center">
                    <!-- {{Api::stampPaid(null,200,200)}} -->
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
                    <p><strong>KES {{number_format($invoice->total()+$Details[0]->Arrears,2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->receipted(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->paid(),2)}}</strong></p>
                    <p><strong>KES {{number_format($invoice->balance()+$Details[0]->Arrears,2)}}</strong></p>
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
			  <!-- <a href="{{route('portal.payment', [ 'invoice' => ','.$invoice->id() ]) }}"> 
          <button> Confirm Payment </button> </a> -->
			  <a href="{{route('application.invoicepdf', [ 'hid' => $invoice->id()])}}"><button>View As Pdf</button></a> </td>			  
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
    $(document).ready(function() {
      console.log('ready')        ;
    });
  </script>
@endsection
