@extends('dashboard.index')

@section('dashboard-aside')
  <div id="settings-menu" class="ui secondary vertical pointing menu">

    <a id="account" class="red item" href="{{route('portal.account')}}">Profile</a>
    <a id="signage" class="red item" href="{{route('portal.account')}}">Users</a>
    <a id="signage" class="red item" href="{{route('portal.account')}}">Businesses</a>

  </div>
@endsection

@section('script')
  @parent
  <script type="text/javascript">
     $( document ).ready(function() {
       $('#dashboard-menu #settings').trigger('click');
     });
  </script>
@endsection
