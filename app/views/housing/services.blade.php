@extends('dashboard.services')


@section('dashboard-content')
  <div class="ui segment">
    @yield('service')
  </div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#department-menu').accordion('open', 2);
     });
  </script>
@endsection
