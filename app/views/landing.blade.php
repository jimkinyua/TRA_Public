@extends('layout')

@section('content')
  <div id="landing" class="ui vertically divided grid">

    <header class="row">
      <div class="three wide column">
        <a href="#" class="ui medium image">
          <img src="{{asset('images/ug-logo.png')}}">
        </a>
      </div>
      <div class="thirteen wide column">
        <div class="middle aligned content">
          <h2 id="heading" class="ui center aligned header">THE COUNTY GOVERNMENT OF UASIN GISHU SELF SERVICE PORTAL </h2>
        </div>
      </div>
    </header>

    <div class="row">
      <div class="ten wide column">
        <div class="ui stacked segment">
          <h4 class="ui header">Online Services</h4>
            <hr/>
            <p>&nbsp</p>

            <p>The County Fiscal Strategy Paper (CFSP) was prepared by the County Treasury in accordance with Section 117 of the Public Finance Management (PFM) Act 2012.</p>
            <p>The Act states that a County Treasury shall prepare and submit to the County Executive Committee the County Fiscal Strategy Paper for approval and the County Treasury shall submit the approved Fiscal Strategy Paper to the county assembly, by the 28th February of each year; and align its County Fiscal Strategy Paper with the national objectives in the Budget Policy Statement.</p>
            <p>The paper was also prepared within the framework defined by the County’s 2013/2014 Budget; CIDP and Kenya Vision 2030.</p>

            <p>&nbsp</p>
        </div>
      </div>

      <div class="six wide column">
        <form class="ui form">

          <h4 class="ui dividing header">Login to access your account</h4>

          <div class="required field">
            <label>Username</label>
            <div class="ui icon input">
              <input type="email" placeholder="Username">
              <i class="user icon"></i>
            </div>
          </div>
          <div class="required field">
            <label>Password</label>
            <div class="ui icon input">
              <input type="password">
              <i class="lock icon"></i>
            </div>
          </div>

          <div class="fluid ui orange submit button">Register</div>

          <div class="ui horizontal divider">
            Can't Login?
          </div>

          <div class="ui two column middle aligned relaxed fitted stackable grid">
            <div class="column">
              <div class="fluid ui green labeled icon button">
                Register
                <i class="add icon"></i>
              </div>
            </div>

           <div class="center aligned column">
             <div class="fluid ui  labeled icon button">
               <i class="signup icon"></i>
               Reset Password
             </div>
           </div>
         </div>

        </form>
      </div>
    </div>

    <div class="fourteen column row">
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
    </div>
    <div class="thirteen column row">
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
    </div>
    <div class="twelve column row">
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
      <div class="column"></div>
    </div>
  </div>
@endsection
