@extends('dashboard.manage')

@section('dashboard-content')

@if(count($reports) == 0)
    <div class="ui orange attached segment">
        <h2 class="ui center aligned icon header">
            <i class="circular hide icon"></i>
            No Land Records
            <div class="sub header">You have not yet made any registration fro land </div>
        </h2>
    </div>
@else
  <div class="ui basic padded segment">
    <h3 class="ui left aligned dividing  header">  Land Reports  </h3>
    <table class="ui selectable attached basic table">
      <thead>
        <tr>
          <th class="">LRN</th>
          <th class="">PlotNo</th>
          <th class="">Date</th>
          <th class="">Receipt</th>
          <th class="">Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($reports as $report)
          <tr>
            <td> <a href="{{route('portal.report', [ 'id' => $report->LaifomsUPN ])}}"> {{$report->LRN}}</a> </td>
            <td> <a href="{{route('portal.report', [ 'id' => $report->LaifomsUPN ])}}"> {{$report->PlotNo}}</a> </td>
            <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($report->DateReceived))->toFormattedDateString() }}</td>
            <td>{{$report->ReceiptNo}}</td>
            <td>KSh. {{number_format($report->Amount, 2)}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
    });
  </script>
@endsection
