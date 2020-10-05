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
       $('#department-menu #hire').trigger('click');
         $('#department-menu').accordion('open', 3);
     });
  </script>
@endsection
