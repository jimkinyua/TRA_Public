@extends('signage.services')

@section('service')
  <div class="ui piled segment">
    <h3> SFTW2 </h2>
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
