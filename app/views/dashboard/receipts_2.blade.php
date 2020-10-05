@extends('dashboard.manage')

@section('dashboard-content')
  @if(count($receipts) == 0)
  <div class="ui orange attached segment">
    <h2 class="ui center aligned icon header">
      <i class="circular hide icon"></i>
      Nothing Here!
      <div class="sub header">You have not yet been issued any payment receipt</div>
    </h2>
  </div>
  @else
    <h5 class="ui center aligned header">
      <div class="ui mini orange horizontal statistic">
        <div class="value">
          
        </div>
        <div class="label">
          Receipts For Invoice Number {{$InvoiceNo}}<br>{{$Description}}
        </div>
      </div>
    </h5>

    <table class="ui attached single line definition table">
      <thead>
        <tr>
          <th></th>
          <th class="five wide">Receipt ID</th>
          <th class="five wide">Date</th>
          <th class="five wide">Amount</th>
        </tr>
      </thead>
      <tbody>
		<?php //print_r($receipts->InvoiceHeaderID); die(); ?>
        @foreach($receipts as $receipt)	
            <tr>
              <td> <a href="{{route('receipt_2.view', [ 'rid' => $receipt->ReceiptID,'hid' => $InvoiceNo ])}}" > View </a> </td>
              <td>{{ $receipt->ReceiptID }}</td>             
              <td>{{ $receipt->CreatedDate }}</td> 
              <td>{{ $receipt->Amount }}</td>              
            </tr>
          
        @endforeach
      </tbody>
    </table>
  @endif

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       //console.log('start')
     });
  </script>
@endsection
