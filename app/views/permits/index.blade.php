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

    <table class="ui compact celled definition table">
      <thead>
        <tr>
          
          <th>Date Submitted</th>
          <th>Service Name</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($applications as $application)
          <tr>
            
            <td>{{$application->Date}}</td>
            <td>{{$application->ServiceName}}</td>
            <td>{{$application->ServiceStatusDisplay}}</td>
            <td> <a href="{{route('permits.view', [ 'id' => $application->ServiceHeaderID ])}}"> <i class="folder open outline icon"></i> </a> </td>
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
     });
  </script>
@endsection
