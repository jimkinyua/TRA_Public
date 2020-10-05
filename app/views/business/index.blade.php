@extends('business.services')

@section('service')
  @if(count($businesses) == 0)
  <div class="ui tall stacked orange segment">
    <h2 class="ui center aligned icon header">
      <i class="circular hide icon"></i>
      Nothing Here!
      <div class="sub header">You have not made any applications for this ervice yet</div>
    </h2>
  </div>
  @else
    <h5 class="ui center aligned header">
      <div class="ui mini orange horizontal statistic">
        <div class="value">
          {{count($businesses)}}
        </div>
        <div class="label">
          Registered Business(es).
        </div>
      </div>
    </h5>

    <table class="ui compact celled definition table">
      <thead>
        <tr>
          <th></th>
          <th>Business Name</th>
          <th>Business Website</th>
          <th>Contact Person</th>
        </tr>
      </thead>
      <tbody>
        @foreach($businesses as $business)
          <tr>
            <td>
              <a href="{{route('view.business', $business->CustomerID)}}">
                <i class="folder open outline icon"></i>
              </a>
            </td>
            <td>{{$business->CustomerName}}</td>
            <td>{{$business->Website}}</td>
            <td>{{$business->ContactPerson}}</td>
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
       $('#registered-businesses').trigger('click');
     });
  </script>
@endsection
