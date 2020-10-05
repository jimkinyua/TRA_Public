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
          Accounts(s).
        </div>
      </div>
    </h5>
<form class="ui form" action="{{ route('portal.get.accounts') }}" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td>
          <div class="ui input">
             <input type="text" name="accname" id="accname" placeholder="Account Name">
          </div>
        <td>
        <td>
          <div class="ui input">
             <input type="text" name="IDNO" id="IDNO" placeholder="ID No">
          </div>
        <td>
        <td>
          <div class="ui input">
             <input type="text" name="mobileno" id="mobileno" placeholder="Mobile No">
          </div>
        <td>
        <td>
          <div class="ui input">
             <input type="text" name="email" id="email" placeholder="Email">
          </div>
        <td>
        <button class="ui fluid green button"> Search...</button>
      </tr>
    </table>
</form>
    <div class="ui hidden divider"></div>

    <table class="ui attached single line definition table portal-table" id="accounts-table" >
      <thead>
        <tr>
          <th></th>
          <th class="Four wide">Account Name</th>
		  <th class="three wide">ID NO</th>
		  <th class="three wide">Mobile No</th>
		  <th class="three wide">Email</th>
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
			<td>{{$customer->IDNO}}</td>
			<td>{{$customer->Mobile1}}</td>
			<td>{{$customer->Email}}</td>
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
