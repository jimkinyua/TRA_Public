@extends('land.services')

@section('dashboard-content')

<div class="ui center aligned segment">
  <div class="ui two item fluid labeled icon menu">
    <a id="register" href="{{route('land.registration')}}" class="item">
      <i class="mail icon"></i>
      Register Land
    </a>
    <a id="pay" href="{{route('land.pay')}}" class="item">
      <i class="lab icon"></i>
      Pay Land Rates
    </a>
  </div>
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
