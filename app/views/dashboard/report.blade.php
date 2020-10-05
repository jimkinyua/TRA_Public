@extends('dashboard.manage')

@section('dashboard-content')
<div class="ui basic padded segment">
  <h3 class="ui left aligned dividing  header">  Payment Reports for {{$reports[0]->UPN}} </h3>
  <table class="ui selectable attached basic table">
    <thead>
      <tr>
        <th class="four wide">Date</th>
        <th class="four wide">Invoice </th>
        <th class="four wide">Receipt</th>
        <th class="four wide">Amount</th>
      </tr>
    </thead>
    <tbody>
      @foreach($reports as $report)
        <tr>
          <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($report->DateReceived))->toFormattedDateString() }}</td>
          <td>{{$report->InvoiceNo}}</td>
          <td>{{$report->ReceiptNo}}</td>
          <td>KSh. {{number_format($report->Amount, 2)}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
    });
  </script>
@endsection
