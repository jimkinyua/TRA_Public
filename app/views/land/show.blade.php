@extends('land.services')

@section('service')

  <table class="ui definition table">
    <thead>
      <tr>
        <th></th>
        <th>Description</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>Land Owner</td>
        <td>{{$land->CustomerName}}</td>
      </tr>
      <tr>
        <td>Land Owner's ID Number</td>
        <td>{{$land->IDNO}}</td>
      </tr>
      <tr>
        <td>Land Owner's Mobile Number</td>
        <td>{{$land->Mobile1}}</td>
      </tr>
      </tr>
      <tr>
        <td>Plot Number</td>
        <td>{{$land->Value}}</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Application Status</td>
        <td>{{$land->ServiceStatusDisplay}}</td>
      </tr>
    </tbody>

    <tfoot class="full-width">
      <tr>
        <th></th>
        <th colspan="4">
          @if ($land->ServiceStatusDisplay === 'Approved Pending Payment')
            <a href="{{route('land.invoice', $land->CustomerID)}}" class="ui right floated small primary labeled icon button">
              <i class="send icon"></i>
              Request Invoice
            </a>
          @else
          <div class="ui ignored warning message">
            <p>This application requires further processing!</p>
          </div>
          @endif
        </th>
      </tr>
    </tfoot>

  </table>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#land-menu #search').trigger('click');
     });
  </script>
@endsection
