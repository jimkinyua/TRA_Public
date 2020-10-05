@extends('land.services')

@section('service')
  <div class="ui piled segment">

    <form class="ui form" method="POST" action="{{route('land.post.search')}}">

      <h4 class="ui dividing header">Search Parameters</h4>

      <div class="required field">
        <label>Plot Number</label>
        <input name="PlotNumber" type="text" placeholder="Land's Plot Number">
      </div>

      <button class="ui submit button">Search</button>

    </form>

  </div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#land-menu #search').trigger('click');
     });
  </script>
@endsection
