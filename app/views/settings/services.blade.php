@extends('dashboard.services')


@section('dashboard-content')
  <div id="signage-menu" class="ui fluid three item pointing menu">
    <a id="application" href="{{route('signage.apply')}}" class=" orange item">
      <i class="add circle icon"></i> Apply
    </a>
    <a id="charges" href="{{route('signage.charges')}}" class="orange item">
      <i class="external share icon"></i> Service Charges
    </a>
    <a id="applications" href="{{route('signage.applications')}}" class=" orange item">
      <i class="print icon"></i> Applications
    </a>
  </div>

  @yield('service')
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#department-menu #signage').trigger('click');
     });
  </script>
@endsection
