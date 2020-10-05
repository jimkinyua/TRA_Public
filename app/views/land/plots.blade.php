@extends('land.services')

@section('service')
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
            <th>Plot Number</th>
            <th>Date Registered</th>
          </tr>
        </thead>
        <tbody>
          @foreach($property as $prop)
            <tr>
              <td class="collapsing">
                <form class="ui form" method="POST" action="{{route('land.post.search')}}">
                  <input type="hidden" name="PlotNumber" value="{{$prop->Value}}" />
                  <button class="ui tiny basic button">
                    <a> <i class="info circle icon"></i> View </a>
                  </button>
                </form>
              </td>
              <td>{{$prop->Value}}</td>
              <td>{{$prop->SubmissionDate}}</td>
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
