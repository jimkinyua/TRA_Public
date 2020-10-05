@extends('land.services')

@section('service')
  <div class="ui basic segment">

    <table class="ui definition table">
      <thead>
        <tr>
          <th></th>
          <th>Plot Number</th>
          <th>Application Status</th>
          <th>Application Date</th>
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
            <td>{{$prop->ServiceStatusName}}</td>
            <td>{{$prop->SubmissionDate}}</td>
          </tr>
        @endforeach
      </tbody>
      <tfoot class="full-width">
        <tr>
          <th></th>
          <th colspan="4">
            <div class="ui right floated small labeled icon button">
              <i class="send icon"></i> Request Invoice for all properties
            </div>
          </th>
        </tr>
      </tfoot>
    </table>

  </div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#land-menu #pay').trigger('click');
     });
  </script>
@endsection
