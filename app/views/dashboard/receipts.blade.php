@extends('dashboard.manage')

@section('dashboard-content')
  @if(count($invoices) == 0)
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
          {{count($invoices)}}
        </div>
        <div class="label">
          Receipt(s).
        </div>
      </div>
    </h5>

    <table class="ui attached single line definition table">
      <thead>
        <tr>
          <th></th>
          <th class="one wide">Reference</th>
          <th class="ten wide">Description</th>
          <th class="three wide">Issued</th>
          <th class="two wide">Paid</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoices as $invoice)
          @if($invoice->receipted() > 0)
            <?php $item = $invoice->items()->first(); ?>
			<?php $items = $invoice->receipts()->first(); ?>
			<?php $recs = $invoice->paid(); ?>			
            <tr>
              <td> <a href="{{route('receipt.view', [ 'ihid' => $invoice->id() ])}}" > Details </a> </td>
              <td>{{ $item->id() }}</td>
              {{--
              <td>{{ $item->service->ServiceName }} : <br/> {{ $item->Description }}</td>
              --}}
              <?php if(!is_null($item->service)) { $name = $item->service->ServiceName; } else { $name = $item->id(); } ?>
              <td> {{ $name }} <br/> {{ $item->Description }}</td>
              <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($item->CreateDate))->toFormattedDateString() }}</td>
              <td>{{ number_format($recs, 2) }}</td>
            </tr>
          @endif
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
