@extends('housing.services')

@section('dashboard-content')

<div class="ui center aligned segment">
  <div class="ui three item fluid labeled icon menu">
    <a id="home" href="{{route('housing.home')}}" class="item">
      <i class="soccer icon"></i>
      Hire House
    </a>
    <a id="stall" href="{{route('housing.stall')}}" class="item">
      <i class="university icon"></i>
      Hire Stall
    </a>
    <a id="applications" href="{{route('housing.applications')}}" class="item">
      <i class="announcement icon"></i>
      View Applications
    </a>
  </div>
</div>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#housing-menu #search').trigger('click');
     });
  </script>
@endsection
