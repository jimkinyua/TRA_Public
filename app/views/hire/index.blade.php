@extends('hire.services')

@section('dashboard-content')

<div class="ui center aligned segment">
  <div class="ui three item fluid labeled icon menu">
    <a id="stadia" href="{{route('hire.stadia')}}" class="item">
      <i class="soccer icon"></i>
      Hire Stadium
    </a>
    <a id="premises" href="{{route('hire.premises')}}" class="item">
      <i class="university icon"></i>
      Hire Premise
    </a>
    <a id="equipment" href="{{route('hire.equipment')}}" class="item">
      <i class="announcement icon"></i>
      Hire Equipment
    </a>
  </div>
</div>

@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#hire-menu #search').trigger('click');
     });
  </script>
@endsection
