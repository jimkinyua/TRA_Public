@extends('dashboard.services')


@section('dashboard-content')
  @yield('service')
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#department-menu #weights').trigger('click');
       $('#department-menu').accordion('open', 5);
     });
  </script>
@endsection
