@extends('dashboard.index')

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#dashboard-menu #support').trigger('click');
     });
  </script>
@endsection
