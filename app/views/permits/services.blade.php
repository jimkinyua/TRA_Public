@extends('dashboard.services')


@section('dashboard-content')
    @yield('service')
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#department-menu').accordion('open', 1);
       $('#department-menu #permits').trigger('click');
     });
  </script>
@endsection
