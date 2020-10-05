@extends('dashboard.manage')

@section('dashboard-content')
  @if(count($property) == 0)
    <div class="ui tall stacked orange segment">
      <h2 class="ui center aligned icon header">
        <i class="circular hide icon"></i>
        Nothing Here!
        <div class="sub header">No Records</div>
      </h2>
    </div>
    @else
    <div class="ui basic segment">

      <table class="ui definition table">
        <thead>
          <tr>
			<th></th>
		    <th>Authority</th>
            <th>Block Number</th>
            <th>Plot Number</th>
            <th>UPN</th>
          </tr>
        </thead>
        <tbody>
          @foreach($property as $prop)
            <tr>
			  <td>
			  <a href="{{route('view.statement', [ 'lrn' => $prop->LRN,'plotno' => $prop->PlotNo,'authority' => $prop->LocalAuthorityID,'upn' => $prop->UPN ])}}">
                Statement <i class="edit icon"></i>
              </a>
			  </td>
              <td>{{$prop->LocalAuthorityID}}</td>
              <td>{{$prop->LRN}}</td>
              <td>{{$prop->PlotNo}}</td>
              <td>{{$prop->UPN}}</td>
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
