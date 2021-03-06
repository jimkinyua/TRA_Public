                                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="ui small fluid secondary vertical accordion menu" >
  @if (Auth::User()->personal_account_id == 3014)
  <a href="{{ url('/dashboard/config') }}" class="ui item">
    <i class="settings icon"></i> Setup
  </a>
  @endif

  <!--
  <a href="{{ url('/dashboard') }}" class="item">
    <i class="alarm outline icon"></i> Alerts
  </a>
-->
  <a href="{{ url('/dashboard/profile') }}" class="item">
    <i class="unlock alternate icon"></i> Profile
  </a>
  @if(Auth::user()->agentAccount->type == 'personal')
  <a href="{{ url('/dashboard/businesses') }}" class="item">
    <i class="suitcase icon"></i> Businesses
  </a>
  @else
  <a href="{{ url('/dashboard/businesses') }}" class="item">
    <i class="suitcase icon"></i> Permits
  </a>
  @endif
  <div class="item">
    <a class="title">  <i class="dropdown icon"></i> Land  </a>
    <div class="content">
      <div class="menu">
        <a class="item" href="{{ route('plot.create') }}">Register Land</a>
        <a class="item" href="{{ route('plot.index') }}">Registered Plots</a>
        <a class="item" href="{{ url('/dashboard/plots') }}">Registration Applications</a>
      </div>
    </div>
  </div>
  <a href="{{ url('/dashboard') }}" class="item">
    <i class="folder outline icon"></i> My Applications
  </a>
  <!--
  <a href="{{ route('plot.index') }}" class="item">
    <i class="fax icon"></i> Invoices
  </a>
-->

  @if (Auth::user()->agentAccount->type == 'business')
  <a href="{{ route('plot.index') }}" class="item">
    <i class="payment icon"></i> Permits
  </a>
  @endif
</div>
