@extends('permits.services')

@section('service')
  @if(count($applications) == 0)
  <div class="ui tall raised orange segment">
    <h2 class="ui center aligned icon header">
      <i class="circular hide icon"></i>
      Nothing Here!
      <div class="sub header">You do not have expired applications to renew</div>
    </h2>
  </div>
  @else
    <h6 class="ui center aligned header">
      <div class="ui mini orange horizontal statistic">
        <div class="value">
          {{count($applications)}} Expired Application(s).
        </div>
      </div>
    </h5>

    <table class="ui compact celled definition table">
      <thead>
        <tr>
          <th></th>
          <th>Date Submitted</th>
          <th>Service Name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($applications as $application)
          <tr>
            <td class="ui center aligned"> <a href="{{route('permits.extend', $application->ServiceHeaderID)}}"><i class="unhide icon"></i> Renew </a> </td>
            <td>{{$application->Date}}</td>
            <td>{{$application->ServiceName}}</td>
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
       $('#permits-menu #renewal').trigger('click');
     });
  </script>
@endsection
