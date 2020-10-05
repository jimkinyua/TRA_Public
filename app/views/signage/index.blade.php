@extends('signage.services')

@section('dashboard-content')

<div class="ui center aligned segment">
  <div class="ui three item fluid labeled icon menu">
    <a id="application" href="{{route('signage.apply')}}" class="item">
      <i class="plus square outline icon"></i>
      Apply for Advertisement
    </a>
    <a id="charges" href="{{route('signage.charges')}}" class="item">
      <i class="wait icon"></i>
      View Service Charges
    </a>
    <a id="applications" href="{{route('signage.applications')}}" class="item">
      <i class="wait icon"></i>
      View Applications
    </a>
  </div>
</div>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#signage-menu #search').trigger('click');
     });
  </script>
@endsection
