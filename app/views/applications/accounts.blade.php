@extends('dashboard.manage')

@section('dashboard-content')
  @if(count($customers) == 0)
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
          {{count($customers)}}
        </div>
        <div class="label">
          Application(s).
        </div>
      </div>
    </h5>

    <table class="ui attached single line definition table" id="accounts-table">
      <thead>
        <tr>
          <th></th>
          <th class="ten wide">Account Name</th>
          <th class="three wide">Account Type</th>
        </tr>
      </thead>
      <tbody>
        @foreach($customers as $customer)
          <tr>
            <td>
              <a href="{{ route('switch.account', [ 'cid' => $customer->CustomerID ]) }}" > Use  </a>
            </td>
            <td>{{$customer->CustomerName}}</td>
            <td>{{$customer->Type}}</td>
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
       console.log('here');
       $('#accounts-table').DataTable();
     });
  </script>
@endsection
