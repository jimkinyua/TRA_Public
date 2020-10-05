<div class="ui centered grid">
  <div class="column">

    <div id="dashboard-header" class="ui basic teal segment">

      <h5 class="ui left floated header">
        <span class="ui large teal label">
          <i class="pin icon"></i>
          Logged In As: <a href="{{route('business.profile')}}" class="header"> {{Session::get('customer')}}</a>
        </span>
      </h5>

      <h6 class="ui right floated header">
        <div class="ui  relaxed horizontal divided list">

          <div class="item">
            <i class="ui user icon"></i>
            <div class="content">
              <a href="{{route('my.profile')}}" class="header">{{Auth::User()}}</a>
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

<div id="dashboard-menu" class="ui centered grid">
  <div class="column">

    <div class="ui secondary pointing menu {{Session::get('customer')->Type}}">

      <a class="item" id="manage" href="{{route('portal.manage')}}">
        <i class="tasks icon"></i> Dashboard
      </a>

      <a class="item" id="services" href="{{route('portal.home')}}">
        <i class="tasks icon"></i> Services
      </a>

      <a class="item" id="bill" href="{{route('portal.services')}}">
        <i class="tasks icon"></i>Tourism Services and Activities
      </a>

      <div class="right menu">
        <div class="ui secondary pointing menu">
          @if(Session::get('county'))
            <div class="item">
              <a href="{{route('redirect.away')}}" class="ui button"> County Admin Portal </a>
            </div>
          @endif
          <div class="accounts ui dropdown item">
            Accounts
            <i class="dropdown icon"></i>
            <div class="menu">
              @foreach(Session::get('customers') as $customer)
                <div class="item" >
                  @if($customer->CustomerID == (Session::get('customer')->CustomerID))
                    <a href="{{route('switch.account', $customer->CustomerID)}}" class="ui fluid inverted purple active button">
                  @else
                    <a href="{{route('switch.account', $customer->CustomerID)}}" class="ui fluid button">
                  @endif
                    {{$customer->CustomerName}}
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
