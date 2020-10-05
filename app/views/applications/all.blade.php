@extends('dashboard.manage')

@section('dashboard-content')
  @if(count($applications) == 0)
  <div class="ui orange attached segment">
    <h2 class="ui center aligned icon header">
      <i class="circular hide icon"></i>
      Nothing Here!
      <div class="sub header">You have not yet applied for any service</div>
    </h2>
  </div>
  @else
    <h5 class="ui center aligned header">
      <div class="ui mini orange horizontal statistic">
        <div class="value">
          {{count($applications)}}
        </div>
        <div class="label">
          Application(s).
        </div>
      </div>
    </h5>

    <table class="ui attached single line definition table portal-table" id="applications-table" >
      <thead>
        <tr>
          <th></th>
          <th class="one wide">Reference</th>
          <th class="ten wide">Service Name</th>
          <th class="three wide">Date Submitted</th>
          <th class="two wide right aligned">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($applications as $application)
          <tr>
            <!--
            <td> <a href="{{route('view.application', [ 'id' => $application->ServiceHeaderID ])}}"> <i class="folder open outline icon"></i> </a> </td>
          -->
            <td>
              <a href="{{route('view.application', [ 'id' => $application->ServiceHeaderID ])}}">
                View <i class="edit icon"></i>
              </a>
            </td>
            <td>{{$application->ServiceHeaderID}}</td>
            <td>{{$application->ServiceName}}</td>
            <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($application->Date))->toFormattedDateString() }}</td>
            <td>{{$application->ServiceStatusDisplay}}</td>
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
       $('#dashboard-menu #manage').trigger('click');
       $('#applications-table').DataTable();
     });
  </script>
@endsection
