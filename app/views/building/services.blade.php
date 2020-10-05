@extends('dashboard.services')


@section('dashboard-content')
  <div id="building-menu" class="ui fluid four item pointing menu">
    <a id="approval" href="{{route('building.approval')}}" class=" red item">
      <i class="building outline icon"></i> Approval
    </a>
    <a id="occupation" href="{{route('building.occupation')}}" class="red item">
      <i class="university icon"></i> Occupation
    </a>
    <a id="fencing" href="{{route('building.fencing')}}" class=" red item">
      <i class="marker icon"></i> Fencing
    </a>
    <a id="fencing" href="{{route('building.fencing')}}" class=" red item">
      <i class="angle down icon"></i> More
    </a>
  </div>

  @yield('service')
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#department-menu #building').trigger('click');
     });
  </script>
@endsection
