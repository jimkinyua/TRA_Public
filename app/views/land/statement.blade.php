@extends('dashboard.manage')

@section('dashboard-content')
  @if(count($stmt) == 0)
    <div class="ui tall stacked orange segment">
      <h2 class="ui center aligned icon header">
        <i class="circular hide icon"></i>
        Nothing Here!
        <div class="sub header">No Records</div>
      </h2>
    </div>
    @else
    <div class="ui basic segment">

      <table class="ui celled table">
        <thead>
          <tr>			
		    <th>Date</th>
            <th>Bill Number</th>
            <th>Description</th>
            <th>Amount</th>
			<th>Balance</th>
          </tr>
        </thead>
        <tbody>
          @foreach($stmt as $prop)
            <tr>
			  <td>{{$prop->DateReceived}}</td>
              <td>{{$prop->DocumentNo}}</td>
              <td>{{$prop->Description}}</td>
              <td>{{$prop->Amount}}</td>
              <td>{{$prop->Balance}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>
  @endif
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#land-menu #pay').trigger('click');
     });
  </script>
@endsection
