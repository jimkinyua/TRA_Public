@extends('portal')

@section('content')
<div class="ui centered grid">
  <div class="column">

    <div id="dashboard-header" class="ui basic teal segment">

      <h5 class="ui left floated header">
        <span class="ui large teal label">
          <i class="pin icon"></i>
          Business Profile: <?php echo 'True'; ?>
            <a href="{{route('business.profile', [ 'cid' => (Session::get('customer')->CustomerID) ])}}" class="header">
              {{Session::get('customer')}}
            </a>
        </span>
      </h5>

      <h6 class="ui right floated header">
        <div class="ui  relaxed horizontal divided list">

          <div class="item">
			<span>Logged In As: &nbsp;  </span>
            <i class="ui user icon"></i>
			
            <div class="content">
			  
              <a href="{{route('user.profile')}}" class="header">{{Auth::User()}}</a>
            </div>
          </div>

          <div class="item">
            <i class="ui sign out icon"></i>
            <div class="content">
              <a href="{{route('portal.logout')}}" class="header">Sign Out</a>
            </div>
          </div>

        </div>
      </h6>

    </div>

    <div class="ui fitted divider"></div>

  </div>
</div>


<?php
  $style =
    (Session::get('customer')->Type == 'business') ?
    "border-bottom: 2px solid #66c17b; margin: 0 15px; padding: 2px 0;" :
    "border-bottom: 2px solid #d95c5c; margin: 0 15px; padding: 2px 0;"
?>

<div id="dashboard-menu" class="ui grid" style="{{$style}}">
  <div class="twelve wide column" style="padding: 0;">

    <div class="ui secondary pointing menu {{Session::get('customer')->Type}}" style="border-bottom: none;">

      <a class="item" id="manage" href="{{route('portal.manage')}}">
        <i class="tasks icon"></i> Dashboard
      </a>
      <!-- @if(Session::get('customer')->Type == 'business') -->
      <a class="item" id="services" href="{{route('portal.home', [ 'id' =>  Session::get('customer')->BusinessTypeID ])}}">
        <i class="tasks icon"></i> Services
      </a>
      <!-- @endif -->

      <a class="item" id="bill" href="{{route('portal.services')}}">
        <i class="tasks icon"></i> Tourism Services and Activities
      </a>

      <div class="right menu">
        <div class="ui secondary pointing menu" style="border-bottom: none;">
          @if(Session::get('KWS'))
            <div class="item">
              <a href="{{route('redirect.away')}}" class="ui button" style="border-radius: 0;"> KWS Admin Portal </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  

  <div class="four wide column right floated" style="padding: 0;">
    <div class="menu">
      <?php $cid = CustomerAgent::where('AgentID', Auth::User()->id())->where('AgentRoleID', 1)->pluck('CustomerID'); ?>
      <div class="item" style="padding-top: 5px; text-align: right; padding-right: 1em;">
        <a href="{{ route('switch.account', [ 'cid' => $cid ]) }}" > Workspace  </a>
      </div>
    </div>
  </div>


</div>

<div id="dahboard" class="ui grid">
    <div class="five wide column">
      @yield('dashboard-aside')
    </div>
    <div class="eleven wide column">
      <div class="ui attached segment">
        @if(Session::has('message')) @include('partials.notification') @endif
        @if($errors->has())
          <div class="ui red segment" >
            <div class="ui selection list">
              @foreach ($errors->all() as $error)
                <div class="item" style="color: #D95C5C;">{{ $error }}</div>
              @endforeach
            </div>
          </div>
        @endif
        @yield('dashboard-content')
      </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
      $(document).ready(function() {

      });
    </script>
@endsection
