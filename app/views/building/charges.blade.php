@extends('signage.services')

@section('service')
  <div class="ui segment">

    <table class="ui compact celled definition table">
      <thead>
        <tr>
          <th></th>
          <th>Service Name</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach($services as $service)
          <tr>
            <td><i class="orange pin icon"></i> </td>
            <td>{{$service->ServiceName}}</td>
            <td>KSh. {{$service->Amount}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

  </div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#signage-menu #charges').trigger('click');
     });
  </script>
@endsection
